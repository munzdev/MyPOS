define([
    "collections/product/MenuCollection",
    "collections/product/ExtraCollection",
    "collections/distribution/ProductsAvailabilitySpecialExtrasCollection"
], function(MenuCollection, ExtraCollection, ProductsAvailabilitySpecialExtrasCollection){
    "use strict";

    var ProductsAvailabilitySetModel = Backbone.Model.extend({
        defaults: function() {
            return {
                menues: new MenuCollection,
                extras: new ExtraCollection,
                special_extras: new ProductsAvailabilitySpecialExtrasCollection
            };
        },

        parse: function(response)
        {
            response.menues = new MenuCollection(response.menues, {parse: true});
            response.extras = new ExtraCollection(response.extras, {parse: true});
            response.special_extras = new ProductsAvailabilitySpecialExtrasCollection(response.special_extras, {parse: true});

            return response;
        }
    });

    return ProductsAvailabilitySetModel;
});