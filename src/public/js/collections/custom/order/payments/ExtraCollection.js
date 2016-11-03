define([
    "models/order/payments/ExtraModel"
], function(ExtraModel){
    "use strict";

    var ExtraCollection = app.BaseCollection.extend({
        model: ExtraModel
    });

    return ExtraCollection;
});