define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class DistributionPlaceUser extends BaseModel {

        defaults() {
            return {DistributionPlaceid: null,
                    Userid: null,
                    EventPrinterid: null};
        }

        parse(response)
        {
            if('User' in response)
            {
                response.User = new app.models.User.User(response.User, {parse: true});
            }

            if('DistributionPlace' in response)
            {
                response.DistributionPlace = new app.models.DistributionPlace.DistributionPlace(response.DistributionPlace, {parse: true});
            }

            if('EventPrinter' in response)
            {
                response.EventPrinter = new app.models.Event.EventPrinter(response.EventPrinter, {parse: true});
            }

            return super.parse(response);
        }

    }
});