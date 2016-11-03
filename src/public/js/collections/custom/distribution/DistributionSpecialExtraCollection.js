define([
    "models/distribution/DistributionOrdersDetailSpecialExtraModel"
], function(DistributionOrdersDetailSpecialExtraModel){
    "use strict";

    var DistributionSpecialExtraCollection = app.BaseCollection.extend({
        model: DistributionOrdersDetailSpecialExtraModel
    });

    return DistributionSpecialExtraCollection;
});