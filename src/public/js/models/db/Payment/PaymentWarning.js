define(["models/db/Payment/Payment",
        "models/db/Payment/PaymentWarningType"
], function(Payment,
            PaymentWarningType){
    "use strict";

    return class PaymentWarning extends app.BaseModel {

        idAttribute() { return 'PaymentWarningid'; }

        defaults() {
            return {PaymentWarningid: null,
                    Paymentid: null,
                    PaymentWarningTypeid: null,
                    WarningDate: null,
                    MaturityDate: null,
                    WarningValue: 0};
        }

        parse(response)
        {
            if('Payment' in response)
            {
                response.Payment = new Payment(response.Payment, {parse: true});
            }

            if('PaymentWarningType' in response)
            {
                response.PaymentWarningType = new PaymentWarningType(response.PaymentWarningType, {parse: true});
            }

            return super.parse(response);
        }
    }
});