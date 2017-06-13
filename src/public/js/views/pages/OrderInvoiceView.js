define(['Webservice',
        'collections/custom/event/PrinterCollection',
        'views/helpers/CustomerSelectView',
        'views/helpers/CouponSelectView',
        'models/custom/order/OrderUnbilled',
        'text!templates/pages/order-invoice.phtml',
        'text!templates/pages/order-item.phtml',
        'jquery-validate'
], function(Webservice,
            PrinterCollection,
            CustomerSelectView,
            CouponSelectView,
            OrderUnbilled,
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
                    'click #coupon-code-verify': 'verify_coupon',
                    'click #submit': 'finish',
                    'change #paymentTypeList': 'change_payment_type',
                    'popupafterclose #success-popup': 'success_popup_close',
                    'popupafterclose #add-coupon-popup': 'add_coupon_popup_close'};
        }

        initialize(options) {
            _.bindAll(this, "renderOpenOrders",
                            "order_count_up",
                            "order_count_down");

            this.orderUnbilled = new OrderUnbilled();
            this.orderUnbilled.set('Orderid', options.orderid);
            this.paymentTypes = new app.collections.Payment.PaymentTypeCollection;
            this.printers = new PrinterCollection;
            this.customerSelectView = new CustomerSelectView({selectCallback: this.click_btn_select_customer.bind(this)});
            this.couponSelectView = new CouponSelectView({selectCallback: this.click_btn_select_coupon.bind(this)});

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
            this.orderUnbilled.set('PaymentTypeid', this.$('#paymentTypeList').val());
            this.orderUnbilled.save()
                              .done(() => {
                                    if(this.$('#print').prop('checked') == 1)
                                    {
                                        var webservice = new Webservice();
                                        webservice.action = "Invoice/Printing/WithPayments/" + this.orderUnbilled.get('Invoiceid');
                                        webservice.formData = {EventPrinterid: this.$('#printer').val()};
                                        webservice.call();
                                    }

                                    this.$('#success-popup').popup("open");
                                });
        }

        use_coupon() {
            this.couponSelectView.show();
        }

        use_customer() {
            this.customerSelectView.show();
        }

        change_payment_type() {
            let t = this.i18n();

            if(this.$('#paymentTypeList').val() == PAYMENT_TYPE_CASH)
                this.$('#print-receipt-text').text(t.printReceipt);
            else
                this.$('#print-receipt-text').text(t.printInvoice);
        }

        click_btn_select_customer(customer) {
            this.orderUnbilled.set('Customer', customer);
            this.renderOpenOrders();
        }

        click_btn_select_coupon(coupon) {
            this.orderUnbilled.get('UsedCoupons').add(coupon);
            this.renderOpenOrders();
        }

        success_popup_close() {
            if(this.$('#continue').prop('checked'))
            {
                if(this.orderUnbilled.get('All') == true)
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
            this.$('#coupons-list').empty();

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

        render() {
            let t = this.i18n();

            this.registerAppendview(this.customerSelectView);
            this.registerAppendview(this.couponSelectView);

            this.renderTemplate(Template, {printers: this.printers,
                                           paymentTypes: this.paymentTypes});

            this.changePage(this);
        }
    }
} );
