define([
    "app",
    "models/manager/UserRoleModel"
], function(app, UserRoleModel){
    "use strict";

    var UserRoleCollection = Backbone.Collection.extend({

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