define([
    "models/db/DistributionPlace/DistributionPlace"
], function(DistributionPlace){
    "use strict";
    
    return class DistributionPlaceCollection extends Backbone.Collection
    {
        model() { return DistributionPlace; }
        url() {return app.API + "DB/DistributionPlace"}
    }
});