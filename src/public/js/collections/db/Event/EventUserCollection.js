define([
    "app",
    "models/db/Event/EventUser"
], function(app, EventUser){
    "use strict";
    
    return class EventUserCollection extends Backbone.Collection
    {
        model() { return EventUser; }
        url() {return app.API + "DB/Event/EventUser"}
    }
});