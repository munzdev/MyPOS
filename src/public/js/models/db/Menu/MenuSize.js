define([
    "app"
], function(app){
    "use strict";

    return class MenuSize extends Backbone.Model {
        
        idAttribute() { return 'MenuSizeid'; }

        defaults() {
            return {MenuSizeid: 0,
                    Eventid: 0,
                    Name: '',
                    Factor: 0};
        }

    }
});