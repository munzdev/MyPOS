define(['models/custom/invoice/InvoiceModel',
        'collections/custom/event/PrinterCollection',
        'models/custom/order/OrderUnbilled',
        'views/helpers/HeaderView',
        'text!templates/pages/order-invoice.phtml',
        'text!templates/pages/order-item.phtml'
], function(InvoiceModel,
            PrinterCollection,
            OrderUnbilledModel,
            HeaderView,
            Template,
            TemplateItem) {
    "use strict";
    
    return class OrderInvoiceView extends app.PageView
    {
        events() {
            return {'click #button-all': 'select_all',
                    'click #show-all': 'set_mode_all',
                    'click #show-single': 'set_mode_single',
                    'click #submit': 'finish',
                    'popupafterclose #success-popup': 'success_popup_close'};
        }
                
        initialize(options) {
            _.bindAll(this, "renderOpenOrders",
                            "select_all",
                            "order_count_up",
                            "order_count_down",
                            "set_mode_all",
                            "set_mode_single",
                            "finish",
                            "success_popup_close");

            this.invoice = new InvoiceModel();
            this.orderUnbilled = new OrderUnbilledModel();
            this.orderUnbilled.set('Orderid', options.orderid);
            this.printers = new PrinterCollection;
            this.printers.fetch()
                         .done(() => {
                             this.render();
                             this.set_mode_all();
                         });            
        }

        set_mode_all() {
            if(DEBUG) console.log("MODE: all");

            this.orderUnbilled.set('All', true);
            this.orderUnbilled.fetch()
                                .done(this.renderOpenOrders);
        }

        set_mode_single() {
            if(DEBUG) console.log("MODE: single");

            this.orderUnbilled.set('All', false);
            this.orderUnbilled.fetch()
                                .done(this.renderOpenOrders);
        }

        select_all(event) {
            this.orderUnbilled.get('orders').each(function(order){
                order.set('currentInvoiceAmount', order.get('amount')) ;
            });

            this.orderUnbilled.get('extras').each(function(extra){
                extra.set('currentInvoiceAmount', extra.get('amount')) ;
            });

            this.renderOpenOrders();
        }

        order_count_up(event) {
            if(DEBUG) console.log("Up");

            var menu_typeid = $(event.currentTarget).attr('data-menu-typeid');
            var index = $(event.currentTarget).attr('data-index');

            if(menu_typeid > 0)
                var order = this.orderUnbilled.get('orders')
                                         .at(index);
            else
                var order = this.orderUnbilled.get('extras')
                                         .at(index);

            var amount_open = order.get('amount') - order.get('amount_payed');
            var current_amount = order.get('currentInvoiceAmount');
            current_amount++;

            if(current_amount > amount_open && amount_open > 0)
                current_amount = amount_open;
            else if(current_amount > 0 && amount_open < 0)
                current_amount = 0;

            if(menu_typeid > 0)
                this.orderUnbilled.get('orders')
                             .at(index)
                             .set('currentInvoiceAmount', current_amount);
            else
                this.orderUnbilled.get('extras')
                             .at(index)
                             .set('currentInvoiceAmount', current_amount);

            this.renderOpenOrders();
        }

        order_count_down(event) {
            if(DEBUG) console.log("Down");

            var menu_typeid = $(event.currentTarget).attr('data-menu-typeid');
            var index = $(event.currentTarget).attr('data-index');

            if(menu_typeid > 0)
                var order = this.orderUnbilled.get('orders')
                                         .at(index);
            else
                var order = this.orderUnbilled.get('extras')
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
                this.orderUnbilled.get('orders')
                             .at(index)
                             .set('currentInvoiceAmount', current_amount);
            else
                this.orderUnbilled.get('extras')
                             .at(index)
                             .set('currentInvoiceAmount', current_amount);

            this.renderOpenOrders();
        }

        finish() {
            var self = this;
            var webservice = new Webservice();
            webservice.action = "Orders/MakePayment";
            webservice.formData = {orderid: this.orderid,
                                   tableNr: this.tableNr,
                                   mode: this.mode,
                                   payments: JSON.stringify(this.orderUnbilled)};
            webservice.call()
                    .done((result) => {
                        if(this.$('#print').prop('checked') == 1)
                        {
                            var webservice = new Webservice();
                            webservice.action = "Orders/PrintInvoice";
                            webservice.formData = {invoiceid: result,
                                                   printerid: this.$('#printer').val()};
                            webservice.call();
                        }

                        this.$('#success-popup').popup("open");
                    });
        }

        success_popup_close() {
            if(this.$('#continue').prop('checked'))
            {
                if(this.mode == 'all')
                    this.set_mode_all();
                else
                    this.set_mode_single();
            }
            else
                this.changeHash("order-overview");
        }

        renderOpenOrders() {
            var itemTemplate = _.template(TemplateItem);

            this.$('#open-orders-list').empty();

            var sortedOrders = {};

            var totalSumPrice = 0;
            var totalOpenProducts = 0;
            var totalProductsInInvoice = 0;

            this.orderUnbilled.get('orders').each(function(order, index)
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

            this.orderUnbilled.get('extras').each(function(extra, index)
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

            _.each(sortedOrders, (category) => {
                this.$('#open-orders-list').append("<li data-role='list-divider'>" + category.name + "</li>");
                category.orders.each((order) => {
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

                    this.$('#open-orders-list').append("<li>" + itemTemplate(datas) + "</li>");
                });
                category.extras.each((extra) => {
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
                    this.$('#open-orders-list').append("<li>" + itemTemplate(datas) + "</li>");
                });
            });

            if(totalOpenProducts == totalProductsInInvoice)
            {
                this.$('#continue').prop("checked", false).checkboxradio('refresh');
            }

            this.$('#invoice-price').text(parseFloat(totalSumPrice).toFixed(2) + ' €');

            this.$('.order-item-up').click(this.order_count_up);
            this.$('.order-item-down').click(this.order_count_down);
            this.$('#open-orders-list').listview('refresh');
        }

        // Renders all of the Category models on the UI
        render() {
            let header = new HeaderView();
            this.registerSubview(".nav-header", header);
            
            this.renderTemplate(Template, {printers: this.printers});

            this.changePage(this);

            return this;
        }
    }
} );