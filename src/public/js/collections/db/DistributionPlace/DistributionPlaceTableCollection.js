define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class DistributionPlaceTableCollection extends BaseCollection
    {
        getModel() { return app.models.DistributionPlace.DistributionPlaceTable; }
        url() {return app.API + "DB/DistributionPlace/DistributionPlaceTable"}
    }
});