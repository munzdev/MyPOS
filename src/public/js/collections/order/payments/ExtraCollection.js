define([
    "models/order/payments/ExtraModel"
], function(ExtraModel){
    "use strict";

    var ExtraCollection = Backbone.Collection.extend({
        model: ExtraModel
    });

    return ExtraCollection;
});