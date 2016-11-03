define([
    "models/db/DistributionPlace/DistributionPlace"
], function(DistributionPlace){
    "use strict";
    
    return class DistributionPlaceCollection extends app.BaseCollection
    {
        getModel() { return DistributionPlace; }
        url() {return app.API + "DB/DistributionPlace"}
    }
});