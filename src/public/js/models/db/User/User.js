define([
    "app"
], function(){
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

    }
});