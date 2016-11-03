define([
    "models/db/Event/EventPrinter"
], function(EventPrinter){
    "use strict";
    
    return class EventPrinterCollection extends app.BaseCollection
    {
        getModel() { return EventPrinter; }
        url() {return app.API + "DB/Event/EventPrinter"}
    }
});