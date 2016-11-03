define([
    "models/db/User/Message/UserMessage"
], function(UserMessage){
    "use strict";
    
    return class UserMessageCollection extends Backbone.Collection
    {
        model() { return UserMessage; }
        url() {return app.API + "DB/User/Message/UserMessage";}
    }
});