define([
    "models/db/Event/Event",
        
], function(Event){
    "use strict";

    return class DistributionPlaceGroup extends app.BaseModel {

        idAttribute() { return 'DistributionPlaceid'; }
        
        defaults() {
            return {DistributionPlaceid: null,
                    Eventid: null,
                    Name: ''};
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