define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class User extends BaseModel {

        idAttribute() { return 'Userid'; }

        defaults() {
            return {Userid: null,
                    Username: '',
                    Password: '',
                    Firstname: '',
                    Lastname: '',
                    AutologinHash: '',
                    Active: false,
                    Phonenumber: '',
                    CallRequest: null,
                    IsAdmin: false};
        }

        parse(response)
        {
            if('EventUser' in response)
                response.EventUser = new app.models.Event.EventUser(response.EventUser, {parse: true});

            return super.parse(response);
        }
    }
});