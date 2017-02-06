define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class DistributionPlaceCollection extends BaseCollection
    {
        getModel() { return app.models.DistributionPlace.DistributionPlace; }
        url() {return app.API + "DB/DistributionPlace"}
    }
});