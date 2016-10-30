define([
    "app"
], function(app){
    "use strict";

    return class DistributionPlaceTable extends Backbone.Model {
        
        idAttribute() { return 'EventTableid'; }

        defaults() {
            return {EventTableid: 0,
                    DistributionPlaceid: 0,
                    MenuGroupid: 0};
        }

    }
});