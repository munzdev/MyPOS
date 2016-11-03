define([
   "models/db/Menu/Availability"
], function(Availability){
    "use strict";
    
    return class AvailabilityCollection extends app.BaseCollection
    {
        getModel() { return Availability; }
        url() {return app.API + "DB/Menu/Availability"}
    }
});