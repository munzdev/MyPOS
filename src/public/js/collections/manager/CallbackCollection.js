define([
    "app",
    "models/UserModel"
], function(app, UserModel){
    "use strict";

    var CallbackCollection = Backbone.Collection.extend({

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