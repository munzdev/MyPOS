define(["models/db/Payment/PaymentType",
        "models/db/Invoice/Invoice",
        "models/db/User/User",
        "collections/db/Payment/PaymentCouponCollection"
], function(PaymentType,
            Invoice,
            User,
            PaymentCouponCollection){
    "use strict";

    return class PaymentRecieved extends app.BaseModel {

        idAttribute() { return 'PaymentRecievedid'; }

        defaults() {
            return {PaymentRecievedid: null,
                    Invoiceid: null,
                    PaymentTypeid: null,
                    date: null,
                    amount: 0};
        }

        parse(response)
        {
            if('PaymentType' in response)
            {
                response.PaymentType = new PaymentType(response.PaymentType, {parse: true});
            }

            if('Invoice' in response)
            {
                response.Invoice = new Invoice(response.Invoice, {parse: true});
            }

            if('User' in response)
            {
                response.User = new User(response.User, {parse: true});
            }

            if('PaymentCoupons' in response)
            {
                if(response.PaymentCoupons.toString() == '' || JSON.stringify(response.PaymentCoupons) == '[{"Coupon":[]}]')
                    response.PaymentCoupons = new PaymentCouponCollection();
                else
                    response.PaymentCoupons = new PaymentCouponCollection(response.PaymentCoupons, {parse: true});
            }

            return super.parse(response);
        }
    }
});