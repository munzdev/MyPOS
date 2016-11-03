define([
    "models/db/Payment/PaymentType"
], function(PaymentType){
    "use strict";
    
    return class PaymentTypeCollection extends Backbone.Collection
    {
        model() { return PaymentType; }
        url() {return app.API + "DB/Payment/PaymentType";}
    }
});