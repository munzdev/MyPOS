define([
    "models/distribution/DistributionOrdersDetailSpecialExtraModel"
], function(DistributionOrdersDetailSpecialExtraModel){
    "use strict";

    var DistributionSpecialExtraCollection = Backbone.Collection.extend({
        model: DistributionOrdersDetailSpecialExtraModel
    });

    return DistributionSpecialExtraCollection;
});