define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class UserMessageCollection extends BaseCollection
    {
        getModel() { return app.models.User.Message.UserMessage; }
        url() {return app.API + "DB/User/Message/UserMessage";}
    }
});