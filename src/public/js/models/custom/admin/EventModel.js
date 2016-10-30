define([
    "app"
], function(app){
    "use strict";

    var EventModel = Backbone.Model.extend({

        defaults: {
            eventid: 0,
            name: '',
            date: '',
            active: 0
        },
    });

    return EventModel;
});