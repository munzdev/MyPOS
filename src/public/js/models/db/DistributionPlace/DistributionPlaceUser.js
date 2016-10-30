define([
    "app"
], function(app){
    "use strict";

    return class DistributionPlaceUser extends Backbone.Model {
        
        defaults() {
            return {DistributionPlaceid: 0,
                    Userid: 0,
                    EventPrinterid: 0};
        }

    }
});