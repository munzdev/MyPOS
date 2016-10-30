define([
    "app",
    "models/db/User/UserRole"
], function(app, UserRole){
    "use strict";
    
    return class UserRoleCollection extends Backbone.Collection
    {
        model() { return UserRole; }
        url() {return app.API + "DB/User/UserRole";}
    }
});