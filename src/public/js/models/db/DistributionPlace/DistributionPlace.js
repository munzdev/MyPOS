define([
    "app"
], function(app){
    "use strict";

    return class DistributionPlaceGroup extends Backbone.Model {

        idAttribute() { return 'DistributionPlaceid'; }
        
        defaults() {
            return {DistributionPlaceid: 0,
                    Eventid: 0,
                    Name: ''};
        }

    }
});