/* global _, Backbone, parseFloat */

// Login View
// =============

// Includes file dependencies
define([ "Webservice",
         'models/order/payments/PaymentModel',
         'collections/order/payments/OrderCollection',
         'collections/order/payments/ExtraCollection',
         'collections/PrinterCollection',
         'views/headers/HeaderView',
         'text!templates/pages/order-pay.phtml',
         'text!templates/pages/order-item.phtml'],
function(Webservice,
         PaymentModel,
         OrderCollection,
         ExtraCollection,
         PrinterCollection,
         HeaderView,
         Template,
         TemplateItem) {
    "use strict";

    // Extends Backbone.View
    var OrderPayView = Backbone.View.extend( {

    	title: 'order-pay',
    	el: 'body',

    	events: {
            'click #order-pay-button-all': 'select_all',
            'click #order-pay-show-all': 'set_mode_all',
            'click #order-pay-show-single': 'set_mode_single',
            'click #order-pay-submit': 'finish',
            'popupafterclose #order-pay-success-popup': 'success_popup_close'
    	},

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render",
                            "renderOpenOrders",
                            "select_all",
                            "order_count_up",
                            "order_count_down",
                            "set_mode_all",
                            "set_mode_single",
                            "finish",
                            "success_popup_close");

            this.id = options.id;
            this.tableNr = options.tableNr;

            this.payments = new PaymentModel();
            this.printers = new PrinterCollection;
            this.printerFetchStatus = this.printers.fetch({data: {eventid: app.session.user.get('eventid')},
                                                           success: this.render});

            this.set_mode_all();
        },

        set_mode_all: function()
        {
            console.log("MODE: all");

            this.mode = 'all';

            var self = this;

            $.when(this.printerFetchStatus).done(function(){
                self.payments.fetch({data: {orderid: self.id,
                                            tableNr: self.tableNr},
                                     success: self.renderOpenOrders});
            });
        },

        set_mode_single: function()
        {
            console.log("MODE: single");

            this.mode = 'single';

            var self = this;

            $.when(this.printerFetchStatus).done(function(){
                self.payments.fetch({data: {orderid: self.id},
                                     success: self.renderOpenOrders});
            });
        },

        select_all: function(event)
        {
            this.payments.get('orders').each(function(order){
                order.set('currentInvoiceAmount', order.get('amount')) ;
            });

            this.payments.get('extras').each(function(extra){
                extra.set('currentInvoiceAmount', extra.get('amount')) ;
            });

            this.renderOpenOrders();
        },

        order_count_up: function(event)
        {
            console.log("Up");

            var menu_typeid = $(event.currentTarget).attr('data-menu-typeid');
            var index = $(event.currentTarget).attr('data-index');

            if(menu_typeid > 0)
                var order = this.payments.get('orders')
                                         .at(index);
            else
                var order = this.payments.get('extras')
                                         .at(index);

            var amount_open = order.get('amount') - order.get('amount_payed');
            var current_amount = order.get('currentInvoiceAmount');
            current_amount++;

            if(current_amount > amount_open && amount_open > 0)
                current_amount = amount_open;
            else if(current_amount > 0 && amount_open < 0)
                current_amount = 0;


            if(menu_typeid > 0)
                this.payments.get('orders')
                             .at(index)
                             .set('currentInvoiceAmount', current_amount);
            else
                this.payments.get('extras')
                             .at(index)
                             .set('currentInvoiceAmount', current_amount);

            this.renderOpenOrders();
        },

        order_count_down: function(event)
        {
            console.log("Down");

            var menu_typeid = $(event.currentTarget).attr('data-menu-typeid');
            var index = $(event.currentTarget).attr('data-index');

            if(menu_typeid > 0)
                var order = this.payments.get('orders')
                                         .at(index);
            else
                var order = this.payments.get('extras')
                                         .at(index);

            var amount_open = order.get('amount') - order.get('amount_payed');
            var current_amount = order.get('currentInvoiceAmount');
            current_amount--;

            if((current_amount < 0 && amount_open > 0))
            {
                current_amount = 0;
            }
            else if(current_amount < amount_open && amount_open < 0)
            {
                current_amount = amount_open;
            }

            if(menu_typeid > 0)
                this.payments.get('orders')
                             .at(index)
                             .set('currentInvoiceAmount', current_amount);
            else
                this.payments.get('extras')
                             .at(index)
                             .set('currentInvoiceAmount', current_amount);

            this.renderOpenOrders();
        },

        finish: function()
        {
            var self = this;
            var webservice = new Webservice();
            webservice.action = "Orders/MakePayment";
            webservice.formData = {orderid: this.id,
                                   tableNr: this.tableNr,
                                   mode: this.mode,
                                   payments: JSON.stringify(this.payments)};

            webservice.callback = {
                success: function(result)
                {
                    if($('#order-pay-print').prop('checked') == 1)
                    {
                        var webservice = new Webservice();
                        webservice.action = "Orders/PrintInvoice";
                        webservice.formData = {invoiceid: result,
                                               printerid: $('#order-pay-printer').val()};
                        webservice.call();
                    }

                    $('#order-pay-success-popup').popup("open");
                }
            };
            webservice.call();
        },

        success_popup_close: function()
        {
            if($('#order-pay-continue').prop('checked'))
            {
                if(this.mode == 'all')
                    this.set_mode_all();
                else
                    this.set_mode_single();
            }
            else
                MyPOS.ChangePage("#order-overview");
        },

        renderOpenOrders: function()
        {
            var itemTemplate = _.template(TemplateItem);

            $('#order-pay-open-orders-list').empty();

            var sortedOrders = {};

            var totalSumPrice = 0;
            var totalOpenProducts = 0;
            var totalProductsInInvoice = 0;

            this.payments.get('orders').each(function(order, index)
            {
                var menu_typeid = order.get('menu_typeid');

                if(!(menu_typeid in sortedOrders))
                {
                    sortedOrders[menu_typeid] = {name: order.get('typeName'),
                                                 orders: new OrderCollection,
                                                 extras: new ExtraCollection};
                }

                var extras = order.get('sizeName');

                if(order.get('mixedWith'))
                {
                    extras += ', Gemischt mit: ' + order.get('mixedWith');
                }

                if(order.get('selectedExtras'))
                {
                    extras += ', ' + order.get('selectedExtras');
                }

                if(order.get('extra_detail'))
                {
                    extras += ', ' + order.get('extra_detail');
                }

                order.set('extra_fulltext', extras);
                order.set('index', index);

                sortedOrders[menu_typeid].orders.add(order);

                totalSumPrice += order.get('single_price') * order.get('currentInvoiceAmount');

            });

            this.payments.get('extras').each(function(extra, index)
            {
                if(!(0 in sortedOrders))
                {
                    sortedOrders[0] = {name: "Sonderwünsche",
                                       orders: new OrderCollection,
                                       extras: new ExtraCollection};
                }

                extra.set('index', index);
                sortedOrders[0].extras.add(extra);

                if(extra.get('verified'))
                    totalSumPrice += extra.get('single_price') * extra.get('currentInvoiceAmount');
            });

            _.each(sortedOrders, function(category){
                $('#order-pay-open-orders-list').append("<li data-role='list-divider'>" + category.name + "</li>");
                category.orders.each(function(order){
                    totalOpenProducts += order.get('amount') - order.get('amount_payed');
                    totalProductsInInvoice += order.get('currentInvoiceAmount');

                    var datas = {mode: 'pay',
                                name: order.get('menuName'),
                                extras: order.get('extra_fulltext'),
                                amount: order.get('currentInvoiceAmount'),
                                open: order.get('amount') - order.get('amount_payed'),
                                isSpecialOrder: false,
                                price: order.get('single_price'),
                                totalPrice: order.get('single_price') * order.get('currentInvoiceAmount'),
                                menu_typeid: order.get('menu_typeid'),
                                index: order.get('index'),
                                skipCounts: false};

                    $('#order-pay-open-orders-list').append("<li>" + itemTemplate(datas) + "</li>");
                });
                category.extras.each(function(extra){
                    totalOpenProducts += extra.get('amount') - extra.get('amount_payed');
                    totalProductsInInvoice += extra.get('currentInvoiceAmount');

                    var datas = {mode: 'pay',
                                  name: 'Sonderwunsch',
                                  extras: extra.get('extra_detail'),
                                  amount: extra.get('currentInvoiceAmount'),
                                  open: extra.get('amount') - extra.get('amount_payed'),
                                  isSpecialOrder: extra.get('verified') == 0,
                                  price: extra.get('single_price'),
                                  totalPrice: extra.get('single_price') * extra.get('currentInvoiceAmount'),
                                  menu_typeid: 0,
                                  index: extra.get('index'),
                                  skipCounts: false};
                    $('#order-pay-open-orders-list').append("<li>" + itemTemplate(datas) + "</li>");
                });
            });

            if(totalOpenProducts == totalProductsInInvoice)
            {
                $('#order-pay-continue').prop("checked", false).checkboxradio('refresh');
            }

            $('#order-pay-invoice-price').text(parseFloat(totalSumPrice).toFixed(2) + ' €');

            $('.order-item-up').click(this.order_count_up);
            $('.order-item-down').click(this.order_count_down);
            $('#order-pay-open-orders-list').listview('refresh');
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();
            header.activeButton = 'order-overview';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  printers: this.printers});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);

            return this;
        }
    });

    return OrderPayView;
} );