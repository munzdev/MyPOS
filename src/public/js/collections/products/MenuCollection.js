define([
    "app",
    "MyPOS",
    "models/products/MenuModel"
], function(app, MyPOS, MenuModel){
	"use strict";

    var MenuCollection = Backbone.Collection.extend({
        model: MenuModel
    });

    return MenuCollection;
});