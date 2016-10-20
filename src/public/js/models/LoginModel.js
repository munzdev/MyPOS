define([
    "app"
], function(app){
    "use strict";

    var LoginModel = Backbone.Model.extend({
        urlRoot: app.API + "Users/Login",
        defaults: {
            username: '',
            password: '',
            rememberMe: false
        },

    });

    return LoginModel;
});