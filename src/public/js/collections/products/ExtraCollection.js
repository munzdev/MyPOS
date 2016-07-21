define([
    "app",
    "MyPOS",
    "models/products/ExtraModel"
], function(app, MyPOS, ExtraModel){
	"use strict";

    var ExtraCollection = Backbone.Collection.extend({

        model: ExtraModel

    });

    return ExtraCollection;
});