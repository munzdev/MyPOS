define([
    "models/db/User/UserRole"
], function(UserRole){
    "use strict";
    
    return class UserRoleCollection extends app.BaseCollection
    {
        getModel() { return UserRole; }
        url() {return app.API + "DB/User/UserRole";}
    }
});