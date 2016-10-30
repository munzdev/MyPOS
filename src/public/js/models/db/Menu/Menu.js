define([
    "app"
], function(app){
    "use strict";

    return class Menu extends Backbone.Model {
        
        idAttribute() { return 'Menuid'; }

        defaults() {
            return {Menuid: 0,
                    MenuGroupid: 0,
                    Name: '',
                    Price: 0,
                    Availabilityid: 0,
                    AvailabilityAmount: 0};
        }

    }
});