define([
    "models/db/Event/EventUser"
], function(EventUser){
    "use strict";

    return class User extends app.BaseModel {
        
        idAttribute() { return 'Userid'; }

        defaults() {
            return {Userid: null,
                    Username: '',
                    Password: '',
                    Firstname: '',
                    Lastname: '',
                    AutologinHash: '',
                    Active: false,
                    Phonenumber: '',
                    CallRequest: null,
                    IsAdmin: false};
        }

        parse(response)
        {
            if('EventUser' in response)
                response.EventUser = new EventUser(response.EventUser, {parse: true});
            
            return super.parse(response);
        }
    }
});