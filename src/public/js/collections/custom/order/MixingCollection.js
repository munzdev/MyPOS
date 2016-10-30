define([
    "app",
    "models/order/MixingModel"
], function(app, MixingModel){
    "use strict";

    var MixingCollection = Backbone.Collection.extend({
        model: MixingModel
    });

    return MixingCollection;
});