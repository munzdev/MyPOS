define([
    "models/db/Event/Event",
    
], function(Event){
    "use strict";

    return class EventPrinter extends app.BaseModel {
        
        idAttribute() { return 'EventPrinterid'; }

        defaults() {
            return {EventPrinterid: null,
                    Eventid: null,
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