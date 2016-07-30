define([
    "app",
    "models/distribution/DistributionOrdersDetailModel"
], function(app, DistributionOrdersDetailModel){
    "use strict";

    var DistributionOrdersDetailsCollection = Backbone.Collection.extend({
        model: DistributionOrdersDetailModel
    });

    return DistributionOrdersDetailsCollection;
});