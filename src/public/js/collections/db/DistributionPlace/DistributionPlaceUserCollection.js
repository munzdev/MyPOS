define([
    "models/db/DistributionPlace/DistributionPlaceUser"
], function(DistributionPlaceUser){
    "use strict";
    
    return class DistributionPlaceUserCollection extends app.BaseCollection
    {
        getModel() { return DistributionPlaceUser; }
        url() {return app.API + "DB/DistributionPlace/DistributionPlaceUser"}
    }
});