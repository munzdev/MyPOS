define(['Webservice',
        'collections/custom/event/PrinterCollection',
        'views/helpers/CustomerSelectView',
        'views/helpers/CouponSelectView',
        'views/helpers/OrderItemsView',
        'models/custom/order/OrderUnbilled',
        'text!templates/pages/order-invoice.phtml'
], function(Webservice,
            PrinterCollection,
            CustomerSelectView,
            CouponSelectView,
            OrderItemsView,
            OrderUnbilled,
            Template) {
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
            let i18n = this.i18n();            

            this.orderUnbilled = new OrderUnbilled();
            this.orderUnbilled.set('Orderid', options.orderid);
            this.paymentTypes = new app.collections.Payment.PaymentTypeCollection();
            this.printers = new PrinterCollection();
            this.customerSelectView = new CustomerSelectView({selectCallback: this.click_btn_select_customer.bind(this)});
            this.couponSelectView = new CouponSelectView({selectCallback: this.click_btn_select_coupon.bind(this)});
            this.orderItemsView = new OrderItemsView({mode: 'pay',
                                                      skipCounts: false,
                                                      statusInformation: false,
                                                      countCallback: this.onDataFetched.bind(this)});  
                                                  
            this.render();
                          
            let preFetchDataHandler = $.when(this.printers.fetch(), this.paymentTypes.fetch());
            this.fetchData(preFetchDataHandler, i18n.loadingPreFetchDatas, this.renderPreFetchDatas.bind(this));          
        }
        
        renderPreFetchDatas() {
            let t = this.i18n();
            
            this.printers.each((printer) => {
                let option = $('<option/>', {value: printer.get('EventPrinterid')});
                option.html(t.printer + ': ' + printer.get('Name'));
                this.$('#printer').append(option);
            });               
            
            this.paymentTypes.each((paymentType) => {
                let option = $('<option/>', {value: paymentType.get('PaymentTypeid')});
                option.html(t.payType + ': ' + app.i18n.template.PaymentType[paymentType.get('Name')]);
                this.$('#paymentTypeList').append(option);
            });
            
            this.$('#printer').selectmenu('refresh');
            this.$('#paymentTypeList').selectmenu('refresh');
            
            this.set_mode_all();
        }

        set_mode_all() {
            if(DEBUG) console.log("MODE: all");
            
            let t = this.i18n();

            this.orderUnbilled.set('All', true);
            this.fetchData(this.orderUnbilled.fetch(), t.loadingOrder); 
        }

        set_mode_single() {
            if(DEBUG) console.log("MODE: single");
            
            let t = this.i18n();

            this.orderUnbilled.set('All', false);
            this.fetchData(this.orderUnbilled.fetch(), t.loadingOrder); 
        }

        select_all() {
            this.orderUnbilled.get('UnbilledOrderDetails').each(function(orderDetail) {
                if (orderDetail.get('Verified')) {
                    orderDetail.set('AmountSelected', orderDetail.get('AmountLeft')) ;
                }   
            });

            this.onDataFetched();
        }

        finish() {
            let t = this.i18n();
            let amountSelected = 0;
            
            this.orderUnbilled.get('UnbilledOrderDetails').each((orderDetail) => {
                amountSelected += orderDetail.get('AmountSelected');
            });
            
            if (!amountSelected) {
                app.error.showAlert(t.error, t.errorNothingSelected);
                return;
            }
            
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
            this.onDataFetched();
        }

        click_btn_select_coupon(coupon) {
            this.orderUnbilled.get('UsedCoupons').add(coupon);
            this.onDataFetched();
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

        onDataFetched() {
            let t = this.i18n();
            
            this.$('#coupons-list').empty();
            this.$('#open-orders-list').empty();
            
            this.orderItemsView.orderDetails = this.orderUnbilled.get('UnbilledOrderDetails');
            let detailData = this.orderItemsView.render();
            let totalSumPrice = detailData.totalSumPrice;
            let totalOpenProducts = detailData.totalOpenProducts;
            let totalProductsInInvoice = detailData.totalProductsInInvoice;
            this.$('#open-orders-list').append(this.orderItemsView.$el);                                   

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
                    text +=  ", " + t.value + ": " + app.i18n.toCurrency(coupon.get('Value'));
                    text +=  ", " + t.consumed + ": " + app.i18n.toCurrency(usedValue);

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

            this.$('#invoice-price').text(app.i18n.toCurrency(totalSumPrice));
            this.$('#invoice-price-without-coupon').text(t.withoutCoupon + ': ' + app.i18n.toCurrency(totalSumPriceWithoutCoupon));

            this.$('#coupons-list').listview('refresh');
        }

        render() {
            this.registerAppendview(this.customerSelectView);
            this.registerAppendview(this.couponSelectView);

            this.renderTemplate(Template);

            this.changePage(this);
        }
    }
} );
