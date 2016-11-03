define([
    "models/db/Event/Event",
    
], function(Event){
    "use strict";

    return class EventTable extends Backbone.Model {
        
        idAttribute() { return 'EventTableid'; }

        defaults() {
            return {EventTableid: 0,
                    Eventid: 0,
                    Name: '',
                    Data: ''};
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