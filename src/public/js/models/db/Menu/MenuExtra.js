define([
    "models/db/Event/Event",
    "models/db/Menu/Availability",
    
], function(Event,
            Availability){
    "use strict";

    return class MenuExtra extends app.BaseModel {
        
        idAttribute() { return 'MenuExtraid'; }

        defaults() {
            return {MenuExtraid: null,
                    Eventid: null,
                    Name: '',
                    Availabilityid: null,
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