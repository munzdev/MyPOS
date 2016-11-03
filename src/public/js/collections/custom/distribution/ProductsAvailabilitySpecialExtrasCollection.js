define([
    "models/distribution/ProductsAvailabilitySpecialExtraModel"
], function(ProductsAvailabilitySpecialExtraModel){
    "use strict";

    var ProductsAvailabilitySpecialExtrasCollection = app.BaseCollection.extend({
        model: ProductsAvailabilitySpecialExtraModel
    });

    return ProductsAvailabilitySpecialExtrasCollection;
});