define([
    "models/distribution/DistributionOrdersDetailModel"
], function(DistributionOrdersDetailModel){
    "use strict";

    var DistributionOrdersDetailsCollection = Backbone.Collection.extend({
        model: DistributionOrdersDetailModel
    });

    return DistributionOrdersDetailsCollection;
});