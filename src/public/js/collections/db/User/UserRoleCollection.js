define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class UserRoleCollection extends BaseCollection
    {
        getModel() { return app.models.User.UserRole; }
        url() {return app.API + "DB/User/UserRole";}
    }
});