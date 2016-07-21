define([
    "app",
    "MyPOS",
    "models/order/MixingModel"
], function(app, MyPOS, MixingModel){
	"use strict";

    var MixingCollection = Backbone.Collection.extend({
        model: MixingModel
    });

    return MixingCollection;
});