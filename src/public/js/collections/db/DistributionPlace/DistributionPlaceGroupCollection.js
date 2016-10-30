define([
    "app",
    "models/db/DistributionPlace/DistributionPlaceGroup"
], function(app, DistributionPlaceGroup){
    "use strict";
    
    return class DistributionPlaceGroupCollection extends Backbone.Collection
    {
        model() { return DistributionPlaceGroup; }
        url() {return app.API + "DB/DistributionPlace/DistributionPlaceGroup"}
    }
});