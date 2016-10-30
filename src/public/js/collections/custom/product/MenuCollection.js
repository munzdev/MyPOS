define([
    "app",
    "models/custom/product/MenuModel"
], function(app, MenuModel){
    "use strict";

    var MenuCollection = Backbone.Collection.extend({
        model: MenuModel
    });

    return MenuCollection;
});