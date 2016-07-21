define([
    "app",
    "MyPOS",
    "models/products/MenuModel",
    "collections/order/MixingCollection"
], function(app,
            MyPOS,
            ProductsMenuModel,
            MixingCollection){
    "use strict";

    var MenuModel = ProductsMenuModel.extend({
        defaults: function() {
            return _.extend({}, ProductsMenuModel.prototype.defaults(), {
                backendID: 0,
                amount: 0,
                open: 0,
                extra: '',
                verified: 0,
                mixing: new MixingCollection
            })
        },

        parse: function(response)
        {
            response.mixing = new MixingCollection(response.mixing, {parse: true});
            return ProductsMenuModel.prototype.parse(response);
        }
    });

    return MenuModel;
});