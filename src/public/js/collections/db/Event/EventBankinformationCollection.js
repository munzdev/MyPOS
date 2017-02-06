define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class EventBankinformationCollection extends BaseCollection
    {
        getModel() { return app.models.Event.EventBankinformation; }
        url() {return app.API + "DB/Event/EventBankinformation"}
    }
});