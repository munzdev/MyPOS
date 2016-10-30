define([
    "app",
    "models/db/Event/Event"
], function(app, Event){
    "use strict";
    
    return class EventCollection extends Backbone.Collection
    {
        model() { return Event; }
        url() {return app.API + "DB/Event"}
    }
});