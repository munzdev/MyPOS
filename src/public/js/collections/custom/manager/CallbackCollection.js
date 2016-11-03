define([
    "models/Auth/AuthUserModel"
], function(UserModel){
    "use strict";

    var CallbackCollection = app.BaseCollection.extend({

        model: UserModel,
        url: app.API + "Manager/GetCallbackList/",
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

    return CallbackCollection;
});