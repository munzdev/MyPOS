define([
    "models/db/Event/EventUser"
], function(EventUser){
    "use strict";
    
    return class EventUserCollection extends app.BaseCollection
    {
        getModel() { return EventUser; }
        url() {return app.API + "DB/Event/EventUser"}
    }
});