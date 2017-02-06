define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class PaymentRecieved extends BaseModel {

        idAttribute() { return 'PaymentRecievedid'; }

        defaults() {
            return {PaymentRecievedid: null,
                    Invoiceid: null,
                    PaymentTypeid: null,
                    Date: null,
                    Amount: 0};
        }

        parse(response)
        {
            if('PaymentType' in response)
            {
                response.PaymentType = new app.models.Payment.PaymentType(response.PaymentType, {parse: true});
            }

            if('Invoice' in response)
            {
                response.Invoice = new app.models.Invoice.Invoice(response.Invoice, {parse: true});
            }

            if('User' in response)
            {
                response.User = new app.models.User.User(response.User, {parse: true});
            }

            if('PaymentCoupons' in response)
            {
                if(response.PaymentCoupons.toString() == '' || JSON.stringify(response.PaymentCoupons) == '[{"Coupon":[]}]')
                    response.PaymentCoupons = new app.collections.Payment.PaymentCouponCollection();
                else
                    response.PaymentCoupons = new app.collections.Payment.PaymentCouponCollection(response.PaymentCoupons, {parse: true});
            }

            return super.parse(response);
        }
    }
});