define(function(){
    "use strict";

    var ProductsAvailabilitySpecialExtraModel = Backbone.Model.extend({
        defaults: {
            orders_details_special_extraid: 0,
            extra_detail: '',
            availability: null,
            availability_amount: null
        }
    });

    return ProductsAvailabilitySpecialExtraModel;
});