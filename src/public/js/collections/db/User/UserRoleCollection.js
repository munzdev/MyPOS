define([
    "models/db/User/UserRole"
], function(UserRole){
    "use strict";
    
    return class UserRoleCollection extends Backbone.Collection
    {
        model() { return UserRole; }
        url() {return app.API + "DB/User/UserRole";}
    }
});