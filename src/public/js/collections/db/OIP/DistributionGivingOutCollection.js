define([
    "app",
    "models/db/OIP/DistributionGivingOut"
], function(app, DistributionGivingOut){
    "use strict";
    
    return class DistributionGivingOutCollection extends Backbone.Collection
    {
        model() { return DistributionGivingOut; }
        url() {return app.API + "DB/OIP/DistributionGivingOut";}
    }
});