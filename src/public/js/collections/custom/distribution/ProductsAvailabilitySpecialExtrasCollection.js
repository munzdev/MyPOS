define([
    "models/distribution/ProductsAvailabilitySpecialExtraModel"
], function(ProductsAvailabilitySpecialExtraModel){
    "use strict";

    var ProductsAvailabilitySpecialExtrasCollection = Backbone.Collection.extend({
        model: ProductsAvailabilitySpecialExtraModel
    });

    return ProductsAvailabilitySpecialExtrasCollection;
});