define([
    "models/db/Event/EventTable"
], function(EventTable){
    "use strict";
    
    return class EventTableCollection extends app.BaseCollection
    {
        getModel() { return EventTable; }
        url() {return app.API + "DB/Event/EventTable"}
    }
});