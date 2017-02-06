define(["models/BaseModel"
], function(BaseModel) {
    "use strict";

    return class LoginModel extends BaseModel
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