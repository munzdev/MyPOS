define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class DistributionPlaceGroupCollection extends BaseCollection
    {
        getModel() { return app.models.DistributionPlace.DistributionPlaceGroup; }
        url() {return app.API + "DB/DistributionPlace/DistributionPlaceGroup"}
    }
});