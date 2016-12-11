define([
    "models/db/Event/EventContact"
], function(EventContact){
    "use strict";

    return class EventContactCollection extends app.BaseCollection
    {
        getModel() { return EventContact; }
        url() {return app.API + "DB/Event/EventContact"}
    }
});