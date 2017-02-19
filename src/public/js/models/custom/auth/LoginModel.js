define(["models/BaseModel"
], function(BaseModel) {
    "use strict";

    return class LoginModel extends BaseModel
    {
        url() { return app.API + "Login"; }
        idAttribute() { return 'userid'; }

        defaults()
        {
            return {
                userid: null,
                username: null,
                password: null,
                rememberMe: false
            };
        }
    }
});