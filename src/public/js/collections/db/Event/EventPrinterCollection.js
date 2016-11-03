define([
    "models/db/Event/EventPrinter"
], function(EventPrinter){
    "use strict";
    
    return class EventPrinterCollection extends Backbone.Collection
    {
        model() { return EventPrinter; }
        url() {return app.API + "DB/Event/EventPrinter"}
    }
});