define(['models/custom/payment/PaymentRecievedModel',
        'models/custom/payment/VerifyCoupon',
        'text!templates/pages/invoice-payment.phtml',
        'text!templates/pages/invoice-payment-item.phtml'
], function(PaymentRecievedModel,
            VerifyCoupon,
            Template,
            TemplateItem) {
    "use strict";

    return class InvoicePaymentView extends app.PageView
    {
        initialize(options) {
            this.invoiceid = options.invoiceid;
            this.amount = 0;

            this.paymentTypes = new app.collections.Payment.PaymentTypeCollection();
            this.paymentRecieved = new PaymentRecievedModel();
            this.paymentRecieved.set('PaymentCoupons', new app.collections.Payment.PaymentCouponCollection());

            this.paymentTypes.fetch()
                             .done(() => {
                                 this.render();
                             });
        }

        events() {
            return {'click #use-coupon': 'use_coupon',
                    'click #back': 'click_btn_back',
                    'click #save': 'click_btn_save',
                    'click #coupon-code-verify': 'verify_coupon',
                    'click #coupon-edit-continue': 'click_btn_coupon_edit_continue',
                    'click #finished': 'click_btn_finished',
                    'change #paymentValue': 'renderDetails',
                    'popupafterclose #add-coupon-popup': 'add_coupon_popup_close'}
        }

        use_coupon() {
            this.$('#add-coupon-popup').popup("open");
        }

        verify_coupon() {
            let code = $.trim(this.$('#coupon-code').val());
            let hasCode = false;

            if(code == '')
                return;

            this.paymentRecieved.get('PaymentCoupons').find((paymentCoupon) => {
                if(code == paymentCoupon.get('Coupon').get('Code')) {
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
                        .done((couponData) => {
                            let coupon = new app.models.Payment.Coupon(couponData);
                            let paymentCoupon = new app.models.Payment.PaymentCoupon();

                            paymentCoupon.set('Coupon', coupon);
                            paymentCoupon.set('Couponid', coupon.get('Couponid'));
                            paymentCoupon.set('ValueUsed', coupon.get('Value'));

                            this.paymentRecieved.get('PaymentCoupons').add(paymentCoupon);
                            this.$('#add-coupon-popup').popup("close");
                            this.renderDetails();
                        })
                        .fail(() => {
                            this.$('#add-coupon-popup').popup("close");
                            app.error.showAlert('Fehler!', 'Code nicht gültig oder Gutschein bereits verbraucht!');
                        });
        }

        add_coupon_popup_close() {
            this.$('#coupon-code').val('');
        }

        click_btn_back() {
            window.history.back();
        }

        click_btn_save() {
            if(this.$('#payment-form').valid()) {
                this.$('#verify-dialog').popup('open');
            };
        }

        click_btn_coupon_edit_continue() {
            let valueToUse = $.trim(this.$('#valueToUse').val());
            let paymentCoupon = this.paymentRecieved.get('PaymentCoupons').get({cid: this.editCid});

            if(valueToUse == "" || valueToUse <= 0 || valueToUse > paymentCoupon.get('Coupon').get('Value'))
                return;

            paymentCoupon.set('ValueUsed', valueToUse);
            this.$('#edit-coupon-popup').popup('close');
            this.renderDetails();
        }

        click_btn_finished() {
            this.paymentRecieved.set('Invoiceid', this.invoiceid);
            this.paymentRecieved.set('Amount', this.amount);
            this.paymentRecieved.set('PaymentTypeid', this.$('#typeid').val());
            this.paymentRecieved.save()
                                .done(() => {
                                    this.changeHash("invoice");
                                });
        }

        renderDetails() {
            this.$('#coupons-item-list').empty();
            let template = _.template(TemplateItem);
            let paymentValue = parseFloat(this.$('#paymentValue').val());

            if(!paymentValue)
                paymentValue = 0;

            let couponValue = 0;

            this.paymentRecieved.get('PaymentCoupons').each((paymentCoupon) => {
                couponValue +=  parseFloat(paymentCoupon.get('ValueUsed'));
                this.$('#coupons-item-list').append(template({paymentCoupon: paymentCoupon,
                                                              t: this.i18n(),
                                                              i18n: app.i18n.template}));
            });

            this.$('.edit-btn').click((event) => {
                let itemCid = $(event.currentTarget).attr('data-item-cid');

                let paymentCoupon = this.paymentRecieved.get('PaymentCoupons').get({cid: itemCid});

                this.$('#valueToUse').val(paymentCoupon.get('ValueUsed'));

                this.editCid = itemCid;
                this.$('#edit-coupon-popup').popup('open');
            });

            this.$('.remove-btn').click((event) => {
                let itemCid = $(event.currentTarget).attr('data-item-cid');

                let paymentCoupon =  this.paymentRecieved.get('PaymentCoupons').get({cid: itemCid});

                this.paymentRecieved.get('PaymentCoupons').remove(paymentCoupon);
                this.renderDetails();
            });

            this.amount = paymentValue + couponValue;

            this.$('#coupons-table').table('refresh');
            this.$('#totalPaymentValue').text(app.i18n.toCurrency(paymentValue));
            this.$('#totalCouponValue').text(app.i18n.toCurrency(couponValue));
            this.$('#totalSum').text(app.i18n.toCurrency(this.amount));
        }

        render() {
            let t = this.i18n();

            this.renderTemplate(Template, {paymentTypes: this.paymentTypes});

            // Register new customer form validation
            this.$('#payment-form').validate({
                rules: {
                    paymentValue: {required: true}
                },
                messages: {
                    paymentValue: {required: t.errorPaymentValue}
                },
                errorPlacement: function (error, element) {
                    if(element.is('select'))
                        error.appendTo(element.parent().parent().prev());
                    else
                        error.appendTo(element.parent().prev());
                }
            });

            this.changePage(this);
        }
    }
} );