define(["models/db/User/User",
], function(User){
    "use strict";

    return class AuthUserModel extends User
    {
        urlRoot() { return app.API + "Login/User"; }

        defaults()
        {
            return _.extend(super.defaults(), {EventUser: new app.models.Event.EventUser});
        }
    }
});