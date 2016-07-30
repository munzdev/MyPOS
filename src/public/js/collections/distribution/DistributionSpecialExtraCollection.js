define([
    "app",
    "models/distribution/DistributionOrdersDetailSpecialExtraModel"
], function(app, DistributionOrdersDetailSpecialExtraModel){
    "use strict";

    var DistributionSpecialExtraCollection = Backbone.Collection.extend({
        model: DistributionOrdersDetailSpecialExtraModel
    });

    return DistributionSpecialExtraCollection;
});