define([
    "app"
], function(app){
    "use strict";

    return class EventUser extends Backbone.Model {
        
        idAttribute() { return 'EventUserid'; }

        defaults() {
            return {EventUserid: 0,
                    Eventid: 0,
                    Userid: 0,
                    UserRoles: 0,
                    BeginMoney: 0};
        }

    }
});