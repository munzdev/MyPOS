define([
    "models/db/DistributionPlace/DistributionPlace",
    "models/db/User/User",
    "models/db/Event/EventPrinter",
    
], function(DistributionPlace,
            User,
            EventPrinter){
    "use strict";

    return class DistributionPlaceUser extends app.BaseModel {
        
        defaults() {
            return {DistributionPlaceid: null,
                    Userid: null,
                    EventPrinterid: null};
        }
        
        parse(response)
        {
            if('User' in response)
            {
                response.User = new User(response.User, {parse: true});
            }
            
            if('DistributionPlace' in response)
            {
                response.DistributionPlace = new DistributionPlace(response.DistributionPlace, {parse: true});
            }
            
            if('EventPrinter' in response)
            {
                response.EventPrinter = new EventPrinter(response.EventPrinter, {parse: true});
            }
            
            return super.parse(response);
        }

    }
});