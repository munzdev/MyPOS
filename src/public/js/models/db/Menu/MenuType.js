define([
    "app"
], function(app){
    "use strict";

    return class MenuType extends Backbone.Model {
        
        idAttribute() { return 'MenuTypeid'; }

        defaults() {
            return {MenuTypeid: 0,
                    Eventid: 0,
                    Name: '',
                    Tax: 0,
                    Allowmixing: false};
        }

    }
});