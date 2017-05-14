define(["collections/db/User/UserCollection"
], function(DBUserCollection){
    "use strict";

    return class CallbackCollection extends DBUserCollection
    {
        url() {return app.API + "Manager/Callback"; }
    }
});