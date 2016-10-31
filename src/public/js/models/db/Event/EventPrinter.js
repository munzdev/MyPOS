define([
    "models/db/Event/Event",
    "app"
], function(Event){
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
        
        parse(response)
        {
            if('Event' in response)
            {
                response.Event = new Event(response.Event, {parse: true});
            }
            
            return super.parse(response);
        }

    }
});