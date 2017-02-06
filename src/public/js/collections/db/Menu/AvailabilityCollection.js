define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class AvailabilityCollection extends BaseCollection
    {
        getModel() { return app.models.Menu.Availability; }
        url() {return app.API + "DB/Menu/Availability"}
    }
});