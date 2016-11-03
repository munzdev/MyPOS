define([
    "models/db/DistributionPlace/DistributionPlaceTable"
], function(DistributionPlaceTable){
    "use strict";
    
    return class DistributionPlaceTableCollection extends Backbone.Collection
    {
        model() { return DistributionPlaceTable; }
        url() {return app.API + "DB/DistributionPlace/DistributionPlaceTable"}
    }
});