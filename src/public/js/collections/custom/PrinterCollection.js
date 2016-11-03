define([
    "models/PrinterModel"
], function(PrinterModel){
    "use strict";

    var PrinterCollection = app.BaseCollection.extend({

        model: PrinterModel,
        url: app.API + "Events/GetPrinters/",
        parse: function (response) {
            if(response.error)
            {
                MyPOS.DisplayError(response.errorMessage);
                return null;
    	    }
            else
            {
                return response.result;
            }
        }
    });

    return PrinterCollection;
});