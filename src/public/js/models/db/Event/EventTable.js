define([
    "models/db/Event/Event",
    
], function(Event){
    "use strict";

    return class EventTable extends app.BaseModel {
        
        idAttribute() { return 'EventTableid'; }

        defaults() {
            return {EventTableid: null,
                    Eventid: null,
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