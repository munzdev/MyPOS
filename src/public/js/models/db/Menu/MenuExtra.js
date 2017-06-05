define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class MenuExtra extends BaseModel {

        idAttribute() { return 'MenuExtraid'; }

        defaults() {
            return {MenuExtraid: null,
                    Eventid: null,
                    Name: '',
                    Availabilityid: null,
                    AvailabilityAmount: 0,
                    IsDeleted: null};
        }

        parse(response)
        {
            if('Event' in response)
            {
                response.Event = new app.models.Event.Event(response.Event, {parse: true});
            }

            if('Availability' in response)
            {
                response.Availability = new app.models.Menu.Availability(response.Availability, {parse: true});
            }

            return super.parse(response);
        }
    }
});