define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class UserCollection extends BaseCollection
    {
        getModel() { return app.models.User.User; }
        url() {return app.API + "DB/User";}
    }
});