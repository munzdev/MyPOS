/* global _, Backbone, parseFloat */

// Login View
// =============

// Includes file dependencies
define([ "Webservice",
         'models/order/payments/PaymentModel',
         'collections/order/payments/OrderCollection',
         'collections/order/payments/ExtraCollection',
         'views/headers/HeaderView',
         'text!templates/pages/order-modify-price.phtml',
         'text!templates/pages/order-item.phtml'],
function(Webservice,
         PaymentModel,
         OrderCollection,
         ExtraCollection,
         HeaderView,
         Template,
         TemplateItem) {
    "use strict";

    // Extends Backbone.View
    var OrderModifyPriceView = Backbone.View.extend( {

    	title: 'order-modify-price',
    	el: 'body',

    	events: {
            'click #order-modify-price-list .order-item-edit': 'item_edit',
            'click #order-modify-price-submit': 'finish',
            'click #order-modify-price-dialog-continue': 'click_btn_continue',
            'popupafterclose #order-modify-price-success-popup': 'success_popup_close'
    	},

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render",
                            "renderOpenOrders",
                            "finish",
                            "success_popup_close");

            this.orderid = options.orderid;

            this.payments = new PaymentModel();
            this.modifications = {};

            this.render();

            this.payments.fetch({data: {orderid: this.orderid},
                                 success: this.renderOpenOrders});
        },

        item_edit: function(event)
        {
            var menu_typeid = $(event.currentTarget).attr('data-menu-typeid');
            var index = $(event.currentTarget).attr('data-index');

            if(menu_typeid > 0)
                var order = this.payments.get('orders')
                                         .at(index);
            else
                var order = this.payments.get('extras')
                                         .at(index);

            $('#order-modify-price-dialog-input').val(order.get('single_price'));
            $('#order-modify-price-dialog-continue').attr('data-menu-typeid', menu_typeid);
            $('#order-modify-price-dialog-continue').attr('data-index', index);

            $('#order-modify-price-dialog').popup('open');
        },

        click_btn_continue: function(event)
        {
            var menu_typeid = $(event.currentTarget).attr('data-menu-typeid');
            var index = $(event.currentTarget).attr('data-index');
            var value = parseFloat($('#order-modify-price-dialog-input').val());

            if(isNaN(value) || value < 0)
            {
                MyPOS.DisplayError("Gültigen Preis eingeben!");
                return;
            }

            value = value.toFixed(2);

            if(menu_typeid > 0)
                this.payments.get('orders')
                             .at(index)
                             .set('single_price', value);
            else
                this.payments.get('extras')
                             .at(index)
                             .set('single_price', value);

            if(menu_typeid > 0)
                var id = this.payments.get('orders')
                                       .at(index)
                                       .get('orders_detailid');
            else
                var id = this.payments.get('extras')
                                       .at(index)
                                       .get('orders_details_special_extraid');

            if(this.modifications[menu_typeid] == undefined)
                this.modifications[menu_typeid] = {};

            this.modifications[menu_typeid][id] = value;

            $('#order-modify-price-dialog').popup('close');
            this.renderOpenOrders();
        },

        finish: function()
        {
            var self = this;

            var webservice = new Webservice();
            webservice.action = "Manager/SetPrices";
            webservice.formData = {orderid: this.orderid,
                                   prices: JSON.stringify(this.modifications)};

            webservice.callback = {
                success: function(userid)
                {
                    app.ws.chat.SystemMessage(userid, 'Bei der Bestellung mit der Bestellungsnummer ' + self.orderid + ' wurden vom Manager ' + app.session.user.get('firstname') + ' ' + app.session.user.get('lastname') + ' die Preise angepasst. Bitte die Offenen Bezahlungen/Preise prüfen');
                    $('#order-modify-price-success-popup').popup("open");
                }
            };
            webservice.call();
        },

        success_popup_close: function()
        {
            MyPOS.ChangePage("#order-overview");
        },

        renderOpenOrders: function()
        {
            var itemTemplate = _.template(TemplateItem);

            $('#order-modify-price-list').empty();

            var sortedOrders = {};

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
            });

            _.each(sortedOrders, function(category){
                $('#order-modify-price-list').append("<li data-role='list-divider'>" + category.name + "</li>");
                category.orders.each(function(order){

                    var datas = {mode: 'modify',
                                name: order.get('menuName'),
                                extras: order.get('extra_fulltext'),
                                amount: order.get('amount'),
                                isSpecialOrder: false,
                                price: order.get('single_price'),
                                totalPrice: order.get('single_price') * order.get('amount'),
                                menu_typeid: order.get('menu_typeid'),
                                index: order.get('index'),
                                edit: true,
                                skipCounts: true};

                    $('#order-modify-price-list').append("<li>" + itemTemplate(datas) + "</li>");
                });
                category.extras.each(function(extra){

                    var datas = {mode: 'modify',
                                  name: 'Sonderwunsch',
                                  extras: extra.get('extra_detail'),
                                  amount: extra.get('amount'),
                                  isSpecialOrder: extra.get('verified') == 0,
                                  price: extra.get('single_price'),
                                  totalPrice: extra.get('single_price') * extra.get('amount'),
                                  menu_typeid: 0,
                                  index: extra.get('index'),
                                  edit: true,
                                  skipCounts: true};
                    $('#order-modify-price-list').append("<li>" + itemTemplate(datas) + "</li>");
                });
            });

            $('#order-modify-price-list').listview('refresh');
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();
            header.activeButton = 'order-overview';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render()});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);

            return this;
        }
    });

    return OrderModifyPriceView;
} );