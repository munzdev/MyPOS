define([
    "models/manager/UserRoleModel"
], function(UserRoleModel){
    "use strict";

    var UserRoleCollection = app.BaseCollection.extend({

        model: UserRoleModel,
        url: app.API + "Events/GetRoles/",
        parse: function (response) {
            if(response.error)
            {
                MyPOS.DisplayError(response.errorMessage);
                return null;
    	    }
            else
            {
                return response.result;
            }
        }
    });

    return UserRoleCollection;
});