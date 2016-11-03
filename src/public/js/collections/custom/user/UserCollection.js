define([
    "models/custom/user/UserModel"
], function(UserModel){
    "use strict";
    
    return class UserCollection extends app.BaseCollection
    {
        getModel() {return UserModel}
        url() {return app.API + "User"; }
    }
});