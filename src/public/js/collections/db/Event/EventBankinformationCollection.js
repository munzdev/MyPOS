define([
    "models/db/Event/EventBankinformation"
], function(EventBankinformation){
    "use strict";

    return class EventBankinformationCollection extends app.BaseCollection
    {
        getModel() { return EventBankinformation; }
        url() {return app.API + "DB/Event/EventBankinformation"}
    }
});