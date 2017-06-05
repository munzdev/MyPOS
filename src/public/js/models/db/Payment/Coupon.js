define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class Coupon extends BaseModel {

        idAttribute() { return 'Couponid'; }

        defaults() {
            return {Couponid: null,
                    Eventid: null,
                    CreatedByUserid: null,
                    Code: '',
                    Created: null,
                    Value: 0,
                    IsDeleted: null};
        }

        parse(response)
        {
            if('Event' in response)
            {
                response.Event = new app.models.Event.Event(response.Event, {parse: true});
            }

            if('CreatedByUser' in response)
            {
                response.CreatedByUser = new app.models.User.User(response.CreatedByUser, {parse: true});
            }

            return super.parse(response);
        }

    }
});