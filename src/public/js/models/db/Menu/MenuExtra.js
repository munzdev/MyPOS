define([
    "app"
], function(app){
    "use strict";

    return class MenuExtra extends Backbone.Model {
        
        idAttribute() { return 'MenuExtraid'; }

        defaults() {
            return {MenuExtraid: 0,
                    Eventid: 0,
                    Name: '',
                    Availabilityid: 0,
                    AvailabilityAmount: 0};
        }

    }
});