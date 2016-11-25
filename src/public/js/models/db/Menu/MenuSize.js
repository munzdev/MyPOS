define([
    "models/db/Event/Event",
    
], function(Event){
    "use strict";

    return class MenuSize extends app.BaseModel {
        
        idAttribute() { return 'MenuSizeid'; }

        defaults() {
            return {MenuSizeid: null,
                    Eventid: null,
                    Name: '',
                    Factor: 0};
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