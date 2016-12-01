define(['Webservice',
        'collections/custom/event/PrinterCollection',
        'collections/db/Payment/PaymentTypeCollection',
        'collections/custom/invoice/CustomerSearchCollection',
        'models/custom/order/OrderUnbilled',
        'models/custom/invoice/InvoiceModel',
        'models/custom/payment/VerifyCoupon',
        'models/custom/invoice/CustomerModel',
        'views/helpers/HeaderView',
        'text!templates/pages/order-invoice.phtml',
        'text!templates/pages/order-item.phtml',
        'jquery-validate'
], function(Webservice,
            PrinterCollection,
            PaymentTypeCollection,
            CustomerSearchCollection,
            OrderUnbilled,
            VerifyCoupon,
            CustomerModel,
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
                    'click #use-coupon': 'use_coupon',
                    'click #use-customer': 'use_customer',
                    'click #customer-add': 'use_customer_add',
                    'click #customer-search': "customer_search",
                    'click #coupon-code-verify': 'verify_coupon',
                    'click #submit': 'finish',
                    'popupafterclose #select-customer-popup': 'select_customer_popup_close',
                    'popupafterclose #success-popup': 'success_popup_close',
                    'popupafterclose #add-coupon-popup': 'add_coupon_popup_close'};
        }
                
        initialize(options) {
            _.bindAll(this, "renderOpenOrders",
                            "customer_save",
                            "order_count_up",
                            "order_count_down");

            this.orderUnbilled = new OrderUnbilled();
            this.orderUnbilled.set('Orderid', options.orderid);
            this.paymentTypes = new PaymentTypeCollection;
            this.printers = new PrinterCollection;
            this.customerSearch = new CustomerSearchCollection;
            
            $.when(this.printers.fetch(),
                   this.paymentTypes.fetch())
             .then(() => {
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
            this.orderUnbilled.get('UnbilledOrderDetails').each(function(orderDetail) {
                orderDetail.set('AmountSelected', orderDetail.get('AmountLeft')) ;
            });

            this.renderOpenOrders();
        }

        order_count_up(event) {
            if(DEBUG) console.log("Up");
            
            let index = $(event.currentTarget).attr('data-index');
            let orderDetail = this.orderUnbilled.get('UnbilledOrderDetails').get({cid: index});

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
            let orderDetail = this.orderUnbilled.get('UnbilledOrderDetails').get({cid: index});

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
        
        use_coupon() {
            this.$('#add-coupon-popup').popup("open");
        }
        
        use_customer() {
            this.$('#select-customer-popup').popup("open");
        }
        
        use_customer_add() {
            this.$('#select-customer-popup').popup("close");
            this.$('#add-customer-popup').popup("open");
        }
        
        customer_search() {
            let name = $.trim(this.$('#customer-search-name').val());
            
            if(name == '')
                return;
            
            this.customerSearch.name = name;
            this.customerSearch.fetch()
                                .done(() => {
                                    this.$('#customer-search-result').empty();
                                    let t = this.i18n();
                            
                                    let divider = $('<li/>').attr('data-role', 'list-divider').text(t.searchResult);
                                    this.$('#customer-search-result').append(divider);   
                                    
                                    if(this.customerSearch.length == 0) {
                                        this.$('#customer-search-result').append($('<li/>').text(t.noSearchResult));
                                    } else {
                                        this.customerSearch.each((customer) => {
                                            let a = $('<a/>').attr('class', "customer-search-result-btn ui-btn ui-corner-all ui-shadow ui-btn-b ui-mini ui-icon-check ui-btn-icon-right")
                                                             .attr('data-customercid', customer.cid)
                                                             .text(customer.get('Name'));

                                            this.$('#customer-search-result').append($('<li/>').append(a));
                                        });
                                    }
                                                                        
                                    this.$('.customer-search-result-btn').click((event) => {
                                        let cid = $(event.currentTarget).attr('data-customercid');
                                        this.orderUnbilled.set('Customer', this.customerSearch.get({cid: cid}));                                        
                                        this.$('#select-customer-popup').popup("close");
                                        this.renderOpenOrders();
                                    });
                                    this.$('#customer-search-result').listview('refresh');
                                });                               
        }        
        
        customer_save() {
            if(this.$('#customer-form').valid()) {
                let customer = new CustomerModel;
                customer.set('Title', this.$('#customer-title').val());
                customer.set('Name', this.$('#customer-name').val());
                customer.set('Address', this.$('#customer-address').val());
                customer.set('Address2', this.$('#customer-address2').val() == '' ? null : this.$('#customer-address2').val());
                customer.set('City', this.$('#customer-city').val());
                customer.set('Zip', this.$('#customer-zip').val());
                customer.set('TaxIdentificationNr', this.$('#customer-tin').val() == '' ? null : this.$('#customer-tin').val());
                customer.save()
                        .done(() => {                            
                            this.orderUnbilled.set('Customer', customer);                                                                    
                            this.$('#add-customer-popup').popup("close");
                            this.renderOpenOrders();
                        });
                return false;
            }
        }
        
        verify_coupon() {
            let code = $.trim(this.$('#coupon-code').val());
            let hasCode = false;
           
            if(code == '')
                return;
            
            this.orderUnbilled.get('UsedCoupons').find((coupon) => {
                if(code == coupon.get('Code')) {
                    hasCode = true;
                    return code;
                }
            });
                                   
            if(hasCode) {
                this.$('#add-coupon-popup').popup("close");
                app.error.showAlert('Fehler!', 'Gutschein wurde bereits hinzugefügt!');
                return;
            }
            
            let verifyCoupon = new VerifyCoupon();
            verifyCoupon.set('Code', code);
            verifyCoupon.fetch()
                        .done((coupon) => {
                            this.orderUnbilled.get('UsedCoupons').add(coupon);
                            this.$('#add-coupon-popup').popup("close");
                            this.renderOpenOrders();
                        })
                        .fail(() => {
                            this.$('#add-coupon-popup').popup("close");
                            app.error.showAlert('Fehler!', 'Code nicht gültig oder Gutschein bereits verbraucht!');
                        });
        }

        select_customer_popup_close() {
            this.$('#customer-search-name').val('');
            this.$('#customer-search-result').empty();
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
        
        add_coupon_popup_close() {
            this.$('#coupon-code').val('');
        }

        renderOpenOrders() {
            var itemTemplate = _.template(TemplateItem);

            this.$('#open-orders-list').empty();
            this.$('#coupons-list').empty();

            var sortedOrders = {};

            let sortedCategorys = new Map();
            var totalSumPrice = 0;
            var totalOpenProducts = 0;
            var totalProductsInInvoice = 0;
            let t = this.i18n();
            let currency = app.i18n.template.currency;
                        
            // Presort the list by categorys
            this.orderUnbilled.get('UnbilledOrderDetails').each((orderDetail) => {
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

            let totalSumPriceWithoutCoupon = totalSumPrice;
            
            if(this.orderUnbilled.get('UsedCoupons').length > 0) {
                let divider = $('<li/>').attr('data-role', 'list-divider').text(t.usedCoupons);
                this.$('#coupons-list').append(divider);    
                
                this.orderUnbilled.get('UsedCoupons').each((coupon) => {
                    
                    let orgTotalSumPrice = totalSumPrice;
                    totalSumPrice -= coupon.get('Value');                
                    
                    if(totalSumPrice < 0)
                        totalSumPrice = 0;
                    
                    let usedValue = totalSumPrice > 0 ? coupon.get('Value') : orgTotalSumPrice.toFixed(2);
                    
                    let text = t.code + ": " + coupon.get('Code');
                    text +=  ", " + t.value + ": " + coupon.get('Value') + currency;
                    text +=  ", " + t.consumed + ": " + usedValue + currency;                                      
                    
                    let li = $('<li/>').text(text);
                    
                    this.$('#coupons-list').append(li);
                });
            }

            if(totalOpenProducts == totalProductsInInvoice) {
                this.$('#continue').prop("checked", false).checkboxradio('refresh');
            }
            
            if(this.orderUnbilled.get('Customer') != null) {
                let customer = this.orderUnbilled.get('Customer');
                
                this.$('#selected-customer-display').empty();
                this.$('#selected-customer-display').append('<b>' + t.currentCustomer + ':</b> ' + customer.get('Title') + ' ' + customer.get('Name'));
            }

            this.$('#invoice-price').text(parseFloat(totalSumPrice).toFixed(2) + ' ' + currency);
            this.$('#invoice-price-without-coupon').text(t.withoutCoupon + ': ' + totalSumPriceWithoutCoupon.toFixed(2) + currency);

            this.$('.order-item-up').click(this.order_count_up);
            this.$('.order-item-down').click(this.order_count_down);
            this.$('#open-orders-list').listview('refresh');
            this.$('#coupons-list').listview('refresh');            
        }

        // Renders all of the Category models on the UI
        render() {
            let t = this.i18n();
            let header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template, {printers: this.printers,
                                           paymentTypes: this.paymentTypes});
                                       
            // TODO: Somehow this event is not fired when registered in the events() method.
            // Manualy fix this
            this.$('#customer-save').click(this.customer_save);
            
            // Register new customer form validation
            this.$('#customer-form').validate({
                rules: {
                    title: {required: true},
                    name: {required: true},
                    address: {required: true},
                    city: {required: true},
                    zip: {required: true}
                },
                messages: {
                    title: {required: t.errorTitle},
                    name: {required: t.errorName},
                    address: {required: t.errorAddress},
                    city: {required: t.errorCity},
                    zip: {required: t.errorZip}
                },
                errorPlacement: function (error, element) {                    
                    if(element.is('select'))
                        error.appendTo(element.parent().parent().prev());
                    else
                        error.appendTo(element.parent().prev());
                }
            });         

            this.changePage(this);

            return this;
        }
    }
} );