define([
    "app",
    "models/db/User/Messages/UserMessage"
], function(app, UserMessage){
    "use strict";
    
    return class UserMessageCollection extends Backbone.Collection
    {
        model() { return UserMessage; }
        url() {return app.API + "DB/User/Messages/UserMessage";}
    }
});