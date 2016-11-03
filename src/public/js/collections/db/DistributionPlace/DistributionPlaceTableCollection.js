define([
    "models/db/DistributionPlace/DistributionPlaceTable"
], function(DistributionPlaceTable){
    "use strict";
    
    return class DistributionPlaceTableCollection extends app.BaseCollection
    {
        getModel() { return DistributionPlaceTable; }
        url() {return app.API + "DB/DistributionPlace/DistributionPlaceTable"}
    }
});