define([
    "app",
    "models/db/DistributionPlace/DistributionPlaceUser"
], function(app, DistributionPlaceUser){
    "use strict";
    
    return class DistributionPlaceUserCollection extends Backbone.Collection
    {
        model() { return DistributionPlaceUser; }
        url() {return app.API + "DB/DistributionPlace/DistributionPlaceUser"}
    }
});