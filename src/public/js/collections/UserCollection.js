define([
    "app",
    "models/Auth/AuthUserModel"
], function(app, UserModel){
    "use strict";

    var UserCollection = Backbone.Collection.extend({

        model: UserModel,
        url: app.API + "Users/GetUsersList/",
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

    return UserCollection;
});