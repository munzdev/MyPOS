define([
    "models/db/Event/Event"
], function(Event){
    "use strict";
    
    return class EventCollection extends app.BaseCollection
    {
        getModel() { return Event; }
        url() {return app.API + "DB/Event"}
    }
});