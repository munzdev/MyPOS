define([
    "app"
], function(app){
    "use strict";

    var DistributionPlaceModel = Backbone.Model.extend({

        defaults: {
            distributions_placeid: 0,
            eventid: 0,
            name: ''
        },
    });

    return DistributionPlaceModel;
});