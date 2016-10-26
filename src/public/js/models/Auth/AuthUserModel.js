define([
    "app"
], function(app){
    "use strict";
    
    return class AuthUserModel extends Backbone.Model
    {
        urlRoot() { return app.API + "Login/User"; }
        defaults() {
            return {
                Userid: 0,
                Username: '',
                Firstname: '',
                Lastname: '',
                IsAdmin: 0
            }
        }
    }
});