define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class DistributionGivingOutCollection extends BaseCollection
    {
        getModel() { return app.models.OIP.DistributionGivingOut; }
        url() {return app.API + "DB/OIP/DistributionGivingOut";}
    }
});