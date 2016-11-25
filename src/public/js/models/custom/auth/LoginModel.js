define(function(){
    "use strict";
    
    return class LoginModel extends app.BaseModel
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