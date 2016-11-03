define([
    "models/db/User/User"
], function(User){
    "use strict";
    
    return class UserCollection extends Backbone.Collection
    {
        model() { return User; }
        url() {return app.API + "DB/User";}
    }
});