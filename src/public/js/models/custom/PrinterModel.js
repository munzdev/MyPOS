define([
    "app"
], function(app){
    "use strict";

    var PrinterModel = Backbone.Model.extend({

        defaults: {
            events_printerid: 0,
            name: '',
            default: false,
            ip: '',
            port: 0,
            characters_per_row: 0
        }

    });

    return PrinterModel;
});