define([
    "models/db/Event/Event",
    "models/db/Menu/Availability",
    
], function(Event,
            Availability){
    "use strict";

    return class MenuExtra extends Backbone.Model {
        
        idAttribute() { return 'MenuExtraid'; }

        defaults() {
            return {MenuExtraid: 0,
                    Eventid: 0,
                    Name: '',
                    Availabilityid: 0,
                    AvailabilityAmount: 0};
        }

        parse(response)
        {
            if('Event' in response)
            {
                response.Event = new Event(response.Event, {parse: true});
            }
            
            if('Availability' in response)
            {
                response.Availability = new Availability(response.Availability, {parse: true});
            }
            
            return super.parse(response);
        }
    }
});