define([
   "models/db/Menu/Availability"
], function(Availability){
    "use strict";
    
    return class AvailabilityCollection extends Backbone.Collection
    {
        model() { return Availability; }
        url() {return app.API + "DB/Menu/Availability"}
    }
});