define([
    "models/db/OIP/DistributionGivingOut"
], function(DistributionGivingOut){
    "use strict";
    
    return class DistributionGivingOutCollection extends app.BaseCollection
    {
        getModel() { return DistributionGivingOut; }
        url() {return app.API + "DB/OIP/DistributionGivingOut";}
    }
});