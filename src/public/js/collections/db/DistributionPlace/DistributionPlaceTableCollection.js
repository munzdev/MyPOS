define([
    "app",
    "models/db/DistributionPlace/DistributionPlaceTable"
], function(app, DistributionPlaceTable){
    "use strict";
    
    return class DistributionPlaceTableCollection extends Backbone.Collection
    {
        model() { return DistributionPlaceTable; }
        url() {return app.API + "DB/DistributionPlace/DistributionPlaceTable"}
    }
});