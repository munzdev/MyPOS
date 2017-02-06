define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class EventCollection extends BaseCollection
    {
        getModel() { return app.models.Event.Event; }
        url() {return app.API + "DB/Event"}
    }
});