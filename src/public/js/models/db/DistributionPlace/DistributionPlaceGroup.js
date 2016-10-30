define([
    "app"
], function(app){
    "use strict";

    return class DistributionPlaceGroup extends Backbone.Model {
        
        defaults() {
            return {DistributionPlaceid: 0,
                    MenuGroupid: 0};
        }

    }
});