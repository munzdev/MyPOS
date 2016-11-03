define([
    "models/manager/CheckModel"
], function(CheckModel){
    "use strict";

    var CheckCollection = app.BaseCollection.extend({

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