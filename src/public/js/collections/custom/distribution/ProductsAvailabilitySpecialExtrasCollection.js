define([
    "app",
    "models/distribution/ProductsAvailabilitySpecialExtraModel"
], function(app, ProductsAvailabilitySpecialExtraModel){
    "use strict";

    var ProductsAvailabilitySpecialExtrasCollection = Backbone.Collection.extend({
        model: ProductsAvailabilitySpecialExtraModel
    });

    return ProductsAvailabilitySpecialExtrasCollection;
});