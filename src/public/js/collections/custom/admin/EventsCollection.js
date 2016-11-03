define([
    "models/admin/EventModel"
], function(EventModel){
    "use strict";

    var EventsCollection = app.BaseCollection.extend({
    	model: EventModel,
    	url: app.API + "Admin/GetEventsList/",
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

    return EventsCollection;
});