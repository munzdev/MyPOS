define([
    "app",
    "models/manager/CheckModel"
], function(app, CheckModel){
    "use strict";

    var CheckCollection = Backbone.Collection.extend({

        model: CheckModel,
        url: app.API + "Manager/GetCheckList/",
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

    return CheckCollection;
});