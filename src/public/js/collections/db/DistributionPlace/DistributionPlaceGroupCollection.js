define([
    "models/db/DistributionPlace/DistributionPlaceGroup"
], function(DistributionPlaceGroup){
    "use strict";
    
    return class DistributionPlaceGroupCollection extends app.BaseCollection
    {
        getModel() { return DistributionPlaceGroup; }
        url() {return app.API + "DB/DistributionPlace/DistributionPlaceGroup"}
    }
});