define(function(){
    "use strict";
    
    return class LoginModel extends Backbone.Model
    {
        urlRoot() { return app.API + "Login"; }
        
        defaults()
        {
            return {
                username: '',
                password: '',
                rememberMe: false
            };
        }
    }
});