define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class DistributionSummaryCollection extends BaseCollection
    {
        getModel() { return app.models.Ordering.OrderDetail; }
        url() {return app.API + "DistributionPlace/Summary";}
    }
});