define([
    "app"
], function(app){
    "use strict";

    return class Availability extends Backbone.Model {
        
        idAttribute() { return 'Availabilityid'; }

        defaults() {
            return {Availabilityid: 0,
                    Name: ''};
        }

    }
});