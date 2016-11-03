define([
    "models/db/Event/EventTable"
], function(EventTable){
    "use strict";
    
    return class EventTableCollection extends Backbone.Collection
    {
        model() { return EventTable; }
        url() {return app.API + "DB/Event/EventTable"}
    }
});