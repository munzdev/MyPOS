define([
    "app",
    "models/db/Menu/Availability"
], function(app, Availability){
    "use strict";
    
    return class AvailabilityCollection extends Backbone.Collection
    {
        model() { return Availability; }
        url() {return app.API + "DB/Menu/Availability"}
    }
});