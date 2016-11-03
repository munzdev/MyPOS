define([
    "models/distribution/DistributionOrdersDetailModel"
], function(DistributionOrdersDetailModel){
    "use strict";

    var DistributionOrdersDetailsCollection = app.BaseCollection.extend({
        model: DistributionOrdersDetailModel
    });

    return DistributionOrdersDetailsCollection;
});