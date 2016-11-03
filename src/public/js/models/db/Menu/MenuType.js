define([
    "models/db/Event/Event",
    
], function(Event){
    "use strict";

    return class MenuType extends Backbone.Model {
        
        idAttribute() { return 'MenuTypeid'; }

        defaults() {
            return {MenuTypeid: 0,
                    Eventid: 0,
                    Name: '',
                    Tax: 0,
                    Allowmixing: false};
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