define(function(){
    "use strict";

    var ExtraModel = Backbone.Model.extend({

        defaults: {
            amount: 0,
            single_price: 0,
            extra_detail: null,
            verified: false,
            amount_payed: 0,
            currentInvoiceAmount: 0,
            index: 0
        }

    });

    return ExtraModel;
});