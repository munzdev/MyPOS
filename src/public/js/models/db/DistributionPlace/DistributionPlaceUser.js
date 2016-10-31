define([
    "models/db/DistributionPlace/DistributionPlace",
    "models/db/User/User",
    "models/db/Event/EventPrinter",
    "app"
], function(DistributionPlace,
            User,
            EventPrinter){
    "use strict";

    return class DistributionPlaceUser extends Backbone.Model {
        
        defaults() {
            return {DistributionPlaceid: 0,
                    Userid: 0,
                    EventPrinterid: 0};
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