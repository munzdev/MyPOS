define([
    "models/order/MixingModel"
], function(MixingModel){
    "use strict";

    var MixingCollection = Backbone.Collection.extend({
        model: MixingModel
    });

    return MixingCollection;
});