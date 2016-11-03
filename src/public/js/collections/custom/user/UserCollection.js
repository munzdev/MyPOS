define([
    "models/custom/user/UserModel"
], function(UserModel){
    "use strict";
    
    return class UserCollection extends Backbone.Collection
    {
        model() {return UserModel}
        url() {return app.API + "User"; }
    }
});