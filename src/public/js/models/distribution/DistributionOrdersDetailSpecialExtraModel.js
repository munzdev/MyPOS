define([
    "app"
], function(){
    "use strict";

    var DistributionOrdersDetailSpecialExtraModel = Backbone.Model.extend({
        defaults: {
            orders_details_special_extraid: 0,
            amount: 0,
            extra_detail: ''
        }
    });

    return DistributionOrdersDetailSpecialExtraModel;
});