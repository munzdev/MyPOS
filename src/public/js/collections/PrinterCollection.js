/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS",
    "models/PrinterModel"
], function(app, MyPOS, PrinterModel){
    "use strict";

    var PrinterCollection = Backbone.Collection.extend({

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