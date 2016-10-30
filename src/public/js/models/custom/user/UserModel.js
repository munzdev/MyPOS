define([
    "app"
], function(app){
    "use strict";
    
    return class UserModel extends Backbone.Model
    {
        defaults() {
            return {Userid: 0,
                    EventUserid: 0,
                    Username: '',
                    Firstname: '',
                    Lastname: '',
                    Phonenumber: '',
                    IsAdmin: 0};
        }
    }
});