define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class EventPrinterCollection extends BaseCollection
    {
        getModel() { return app.models.Event.EventPrinter; }
        url() {return app.API + "DB/Event/EventPrinter"}
    }
});