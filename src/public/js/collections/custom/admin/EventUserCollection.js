define([
    "models/admin/EventUserModel"
], function(EventUserModel){
    "use strict";

    var EventUserCollection = app.BaseCollection.extend({
    	model: EventUserModel,
    	url: app.API + "Admin/GetEventUserList/",
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

    return EventUserCollection;
});