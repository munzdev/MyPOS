define([
    "models/db/User/User"
], function(User){
    "use strict";
    
    return class UserCollection extends app.BaseCollection
    {
        getModel() { return User; }
        url() {return app.API + "DB/User";}
    }
});