define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class EventTableCollection extends BaseCollection
    {
        getModel() { return app.models.Event.EventTable; }
        url() {return app.API + "DB/Event/EventTable"}
    }
});