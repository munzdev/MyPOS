define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class EventUser extends BaseModel {

        idAttribute() { return 'EventUserid'; }

        defaults() {
            return {EventUserid: null,
                    Eventid: null,
                    Userid: null,
                    UserRoles: 0,
                    BeginMoney: 0,
                    IsDeleted: null};
        }

        parse(response)
        {
            if('Event' in response)
            {
                response.Event = new app.models.Event.Event(response.Event, {parse: true});
            }

            if('User' in response)
            {
                response.User = new app.models.User.User(response.User, {parse: true});
            }

            return super.parse(response);
        }

    }
});