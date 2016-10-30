define([
    "app",
    "models/admin/EventUserModel"
], function(app, EventUserModel){
    "use strict";

    var EventUserCollection = Backbone.Collection.extend({
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