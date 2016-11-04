define([
    "models/db/User/User",
    "models/db/Event/EventUser"
], function(User,
            EventUser){
    "use strict";
    
    return class AuthUserModel extends User
    {
        urlRoot() { return app.API + "Login/User"; }
        
        defaults()
        {
            return _.extend(super.defaults(), {EventUser: new EventUser});
        }               
    }
});