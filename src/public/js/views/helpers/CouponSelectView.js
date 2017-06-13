define(['models/custom/payment/VerifyCoupon',
        'text!templates/helpers/coupon-select.phtml'
], function(VerifyCoupon,
            Template) {
    "use strict";

    return class CouponSelectView extends app.RenderView
    {
        initialize(options) {
            this.usedCodes = new Set();
            this.selectCallback = options.selectCallback;
        }

        events() {
            return {'click #coupon-code-verify': 'verify_coupon',
                    'popupafterclose #select-coupon-popup': 'select_coupon_popup_close'};
        }

        show() {
            this.$('#select-coupon-popup').popup("open");
        }

        select_coupon_popup_close() {
            this.$('#coupon-code').val('');
        }

        verify_coupon() {
            if (!this.$('#customer-form').valid()) {
                return;
            }

            let t = this.i18n();
            let code = $.trim(this.$('#coupon-code').val());
            let hasCode = false;

            if (this.usedCodes.has(code)) {
                this.$('#select-coupon-popup').popup("close");
                app.error.showAlert(t.error, t.errorCodeUsed);
                return;
            }

            let verifyCoupon = new VerifyCoupon();
            verifyCoupon.set('Code', code);
            verifyCoupon.fetch()
                .done(() => {
                    this.selectCallback(verifyCoupon);
                    this.usedCodes.add(code);
                    this.$('#select-coupon-popup').popup("close");
                })
                .fail(() => {
                    this.$('#select-coupon-popup').popup("close");
                    app.error.showAlert(t.error, t.errorInvalidCode);
                });
        }

        render() {
            let t = this.i18n();
            this.renderTemplate(Template);

            this.$('#coupon-form').validate({
                rules: {
                    couponCode: {required: true}
                },
                messages: {
                    couponCode: {required: t.errorCouponCode}
                },
                errorPlacement: function (error, element) {
                    error.appendTo(element.parent().prev());
                }
            });

            return this;
        }
    }
} );