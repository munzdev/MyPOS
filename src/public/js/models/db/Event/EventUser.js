define([
    "models/db/Event/Event",
    "models/db/User/User",
    
], function(Event,
            User){
    "use strict";

    return class EventUser extends app.BaseModel {
        
        idAttribute() { return 'EventUserid'; }

        defaults() {
            return {EventUserid: null,
                    Eventid: null,
                    Userid: null,
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
            
            return super.parse(response);
        }

    }
});