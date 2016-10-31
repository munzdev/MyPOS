define([
    "models/custom/product/MenuModel",
    "app"
], function(MenuModel){
    "use strict";
    
    return class MenuCollection extends Backbone.Collection
    {
        model() { return MenuModel; }
    }
});