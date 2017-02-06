define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class EventUserCollection extends BaseCollection
    {
        getModel() { return app.models.Event.EventUser; }
        url() {return app.API + "DB/Event/EventUser"}
    }
});