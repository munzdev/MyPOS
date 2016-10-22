define([
    "app"
], function(app){
    "use strict";
    
    return class AuthUserModel extends Backbone.Model
    {
        urlRoot() { return app.API + "Users/Current"; }
        defaults() {
            return {
                userid: 0,
                events_userid: 0,
                username: '',
                firstname: '',
                lastname: '',
                phonenumber: '',
                is_admin: 0,
                user_roles: 0,
                eventid: 0,
                name: '',
                data: ''
            }
        }
    }
});