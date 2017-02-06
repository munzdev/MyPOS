define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class DistributionPlaceGroup extends BaseModel {

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
                response.Event = new app.models.Event.Event(response.Event, {parse: true});
            }

            return super.parse(response);
        }
    }
});