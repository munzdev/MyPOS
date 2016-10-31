define([
    "models/db/Event/Event",
    "app"
], function(Event){
    "use strict";

    return class MenuSize extends Backbone.Model {
        
        idAttribute() { return 'MenuSizeid'; }

        defaults() {
            return {MenuSizeid: 0,
                    Eventid: 0,
                    Name: '',
                    Factor: 0};
        }
        
        parse(response)
        {
            if('Event' in response)
            {
                response.Event = new Event(response.Event, {parse: true});
            }
            
            return super.response(response);
        }

    }
});