define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class EventBankinformation extends BaseModel {

        idAttribute() { return 'EventBankinformationid'; }

        defaults() {
            return {EventBankinformationid: null,
                    Eventid: null,
                    Name: '',
                    Iban: '',
                    Bic: '',
                    Active: false};
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