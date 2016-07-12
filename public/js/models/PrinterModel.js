/**
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS"
], function(app,
            MyPOS){
    "use strict";

    var PrinterModel = Backbone.Model.extend({

        defaults: {
            events_printerid: 0,
            name: '',
            default: false
        }

    });

    return PrinterModel;
});