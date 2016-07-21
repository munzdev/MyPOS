define([
    "app",
    "MyPOS",
    "models/products/SizeModel"
], function(app, MyPOS, SizeModel){
	"use strict";

    var SizeCollection = Backbone.Collection.extend({

        model: SizeModel

    });

    return SizeCollection;
});