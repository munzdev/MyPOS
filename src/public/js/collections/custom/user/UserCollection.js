define([
    "collections/db/User/UserCollection"
], function(DBUserCollection){
    "use strict";
    
    return class UserCollection extends DBUserCollection
    {
        url() {return app.API + "User"; }
    }
});