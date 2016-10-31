define([
    "models/db/Event/Event",
    "models/db/User/User",
    "app"
], function(Event,
            User){
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
        
        parse(response)
        {
            if('Event' in response)
            {
                response.Event = new Event(response.Event, {parse: true});
            }
            
            if('User' in response)
            {
                response.User = new User(response.User, {parse: true});
            }
            
            return super.response(response);
        }

    }
});