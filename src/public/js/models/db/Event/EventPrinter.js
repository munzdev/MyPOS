define([
    "app"
], function(app){
    "use strict";

    return class EventPrinter extends Backbone.Model {
        
        idAttribute() { return 'EventPrinterid'; }

        defaults() {
            return {EventPrinterid: 0,
                    Eventid: 0,
                    Name: '',
                    Ip: '',
                    Port: 0,
                    Default: false,
                    CharactersPerRow: 0};
        }

    }
});