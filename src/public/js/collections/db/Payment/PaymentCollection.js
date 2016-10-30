define([
    "app",
    "models/db/Payment/Payment"
], function(app, Payment){
    "use strict";
    
    return class PaymentCollection extends Backbone.Collection
    {
        model() { return Payment; }
        url() {return app.API + "DB/Payment";}
    }
});