define([
    "models/db/User/Message/UserMessage"
], function(UserMessage){
    "use strict";
    
    return class UserMessageCollection extends app.BaseCollection
    {
        getModel() { return UserMessage; }
        url() {return app.API + "DB/User/Message/UserMessage";}
    }
});