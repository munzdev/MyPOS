define(["collections/db/Event/EventPrinterCollection"
], function(EventPrinterCollection){
    "use strict";

    return class PrinterCollection extends EventPrinterCollection
    {
        url() {return app.API + "Event/Printer";}
    }
});