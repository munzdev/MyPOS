define(function(){
    "use strict";

    var DistributionOrdersDetailSpecialExtraModel = Backbone.Model.extend({
        defaults: {
            orders_details_special_extraid: 0,
            amount: 0,
            extra_detail: '',
            availability_amount: 0
        }
    });

    return DistributionOrdersDetailSpecialExtraModel;
});