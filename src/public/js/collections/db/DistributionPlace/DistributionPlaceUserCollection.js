define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class DistributionPlaceUserCollection extends BaseCollection
    {
        getModel() { return app.models.DistributionPlace.DistributionPlaceUser; }
        url() {return app.API + "DB/DistributionPlace/DistributionPlaceUser"}
    }
});