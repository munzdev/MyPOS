define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class EventContactCollection extends BaseCollection
    {
        getModel() { return app.models.Event.EventContact; }
        url() {return app.API + "DB/Event/EventContact"}
    }
});