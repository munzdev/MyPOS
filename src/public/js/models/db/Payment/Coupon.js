define([
    "models/db/Event/Event",
    "models/db/User/User",
    
], function(Event,
            User){
    "use strict";

    return class Coupon extends app.BaseModel {
        
        idAttribute() { return 'Couponid'; }

        defaults() {
            return {Couponid: null,
                    Eventid: null,
                    CreatedByUserid: null,
                    Code: '',
                    Created: null,
                    Value: 0};
        }
        
        parse(response)
        {
            if('Event' in response)
            {
                response.Event = new Event(response.Event, {parse: true});
            }
            
            if('CreatedByUser' in response)
            {
                response.CreatedByUser = new User(response.CreatedByUser, {parse: true});
            }
            
            return super.parse(response);
        }

    }
});