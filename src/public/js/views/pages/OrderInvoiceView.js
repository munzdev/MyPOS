define(['Webservice',
        'collections/custom/event/PrinterCollection',
        'collections/custom/order/OrderUnbilledCollection',
        'views/helpers/HeaderView',
        'text!templates/pages/order-invoice.phtml',
        'text!templates/pages/order-item.phtml'
], function(Webservice,
            PrinterCollection,
            OrderUnbilledCollection,
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

            this.orderUnbilled = new OrderUnbilledCollection();
            this.orderUnbilled.orderid = options.orderid;
            this.printers = new PrinterCollection;
            this.printers.fetch()
                         .done(() => {
                             this.render();
                             this.set_mode_all();
                         });            
        }

        set_mode_all() {
            if(DEBUG) console.log("MODE: all");

            this.orderUnbilled.all = true;
            this.orderUnbilled.fetch()
                                .done(this.renderOpenOrders);
        }

        set_mode_single() {
            if(DEBUG) console.log("MODE: single");

            this.orderUnbilled.all = false;
            this.orderUnbilled.fetch()
                                .done(this.renderOpenOrders);
        }

        select_all(event) {
            this.orderUnbilled.each(function(orderDetail){
                orderDetail.set('AmountSelected', orderDetail.get('AmountLeft')) ;
            });

            this.renderOpenOrders();
        }

        order_count_up(event) {
            if(DEBUG) console.log("Up");
            
            let index = $(event.currentTarget).attr('data-index');
            let orderDetail = this.orderUnbilled.get({cid: index});

            var amount_open = orderDetail.get('AmountLeft');
            var current_amount = parseFloat(orderDetail.get('AmountSelected'));
            current_amount++;

            if(current_amount > amount_open && amount_open > 0)
                current_amount = amount_open;
            else if(current_amount > 0 && amount_open < 0)
                current_amount = 0;

            orderDetail.set('AmountSelected', current_amount);

            this.renderOpenOrders();
        }

        order_count_down(event) {
            if(DEBUG) console.log("Down");
            
            let index = $(event.currentTarget).attr('data-index');
            let orderDetail = this.orderUnbilled.get({cid: index});

            var amount_open = orderDetail.get('AmountLeft');
            var current_amount = parseFloat(orderDetail.get('AmountSelected'));
            current_amount--;

            if((current_amount < 0 && amount_open > 0))
                current_amount = 0;
            else if(current_amount < amount_open && amount_open < 0)
                current_amount = amount_open;

            orderDetail.set('AmountSelected', current_amount);

            this.renderOpenOrders();
        }

        finish() {
            this.orderUnbilled.save()
                              .done((invoiceid) => {
                                    if(this.$('#print').prop('checked') == 1)
                                    {
                                        var webservice = new Webservice();
                                        webservice.action = "Invoice/Print/" + invoiceid;
                                        webservice.formData = {printerid: this.$('#printer').val()};
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

            let sortedCategorys = new Map();
            var totalSumPrice = 0;
            var totalOpenProducts = 0;
            var totalProductsInInvoice = 0;
            let t = this.i18n();
            let currency = app.i18n.template.currency;
                        
            // Presort the list by categorys
            this.orderUnbilled.each((orderDetail) => {
                let menuid = orderDetail.get('Menuid');
                let key = null;
                
                if(menuid === null && sortedCategorys.get(key) == null) {
                    sortedCategorys.set(key, {name: t.specialOrders,
                                              orders: new Set()});
                } else if(menuid !== null) {
                    let menuSearch = _.find(app.productList.searchHelper, function(obj) {return obj.Menuid == menuid});                    
                    key = menuSearch.MenuTypeid;
                                        
                    if(sortedCategorys.get(key) == null) {
                        let menuType = app.productList.findWhere({MenuTypeid: key});
                        sortedCategorys.set(key, {name: menuType.get('Name'),
                                                  orders: new Set()});
                    }                                                            
                }
                
                sortedCategorys.get(key).orders.add(orderDetail);
            });
            
            for(let[menuTypeid, val] of sortedCategorys.entries()) {
                let divider = $('<li/>').attr('data-role', 'list-divider').text(val.name);
                this.$('#open-orders-list').append(divider);
                let isSpecialOrder = (menuTypeid == null);

                for (let orderDetail of val.orders.values()) {
                    let menuSearch = _.find(app.productList.searchHelper, function(obj) { return obj.Menuid == orderDetail.get('Menuid'); });
                    var extras = '';
                    
                    let menuSize = orderDetail.get('MenuSize');
                    
                    if(!isSpecialOrder && menuSearch.Menu.get('MenuPossibleSize').length > 1)
                        extras = menuSize.get('Name') + ", ";
                    
                    if(orderDetail.get('OrderDetailMixedWiths').length > 0) {
                        extras += t.mixedWith + ": ";

                        orderDetail.get('OrderDetailMixedWiths').each((orderDetailMixedWith) => {
                            let menuToMixWith = _.find(app.productList.searchHelper, function(obj) { return obj.Menuid == orderDetailMixedWith.get('Menuid'); });
                            extras += menuToMixWith.Menu.get('Name') + " - ";
                        });
                        extras = extras.slice(0, -3);
                        extras += ", ";
                    }
                    
                    orderDetail.get('OrderDetailExtras').each(function(extra) {
                        let menuPossibleExtra = menuSearch.Menu.get('MenuPossibleExtra')
                                                                .findWhere({MenuPossibleExtraid: extra.get('MenuPossibleExtraid')});
                        extras += menuPossibleExtra.get('MenuExtra').get('Name') + ", ";
                    });

                    if(orderDetail.get('ExtraDetail') && orderDetail.get('ExtraDetail').length > 0)
                        extras += orderDetail.get('ExtraDetail') + ", ";
                    
                    if(extras.length > 0)
                        extras = extras.slice(0, -2);
                    
                    if(!orderDetail.get('AmountSelected'))
                        orderDetail.set('AmountSelected', 0);
                    
                    let price = parseFloat(orderDetail.get('SinglePrice')) * parseFloat(orderDetail.get('AmountSelected'));
                    totalSumPrice += price;
                    
                    totalOpenProducts += orderDetail.get('AmountLeft');
                    totalProductsInInvoice += parseFloat(orderDetail.get('AmountSelected'));

                    var datas = {mode: 'pay',
                                name: isSpecialOrder ? t.specialOrder : menuSearch.Menu.get('Name'),
                                extras: extras,
                                amount: orderDetail.get('AmountSelected') ? orderDetail.get('AmountSelected') : 0,
                                open: orderDetail.get('AmountLeft'),
                                isSpecialOrder: isSpecialOrder,
                                price: orderDetail.get('SinglePrice'),
                                totalPrice: price,
                                menuTypeid: menuTypeid,
                                index: orderDetail.cid,
                                skipCounts: false,
                                t: app.i18n.template.OrderItem,
                                i18n: app.i18n.template};

                    this.$('#open-orders-list').append("<li>" + itemTemplate(datas) + "</li>");
                }
                
            }            

            if(totalOpenProducts == totalProductsInInvoice)
            {
                this.$('#continue').prop("checked", false).checkboxradio('refresh');
            }

            this.$('#invoice-price').text(parseFloat(totalSumPrice).toFixed(2) + ' ' + currency);

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