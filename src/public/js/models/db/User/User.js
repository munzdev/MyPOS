define([
    "models/db/Event/EventUser"
], function(EventUser){
    "use strict";

    return class User extends Backbone.Model {
        
        idAttribute() { return 'Userid'; }

        defaults() {
            return {Userid: 0,
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