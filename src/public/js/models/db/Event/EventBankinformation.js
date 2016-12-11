define(["models/db/Event/Event"
], function(Event){
    "use strict";

    return class EventBankinformation extends app.BaseModel {

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
                response.Event = new Event(response.Event, {parse: true});
            }

            return super.parse(response);
        }

    }
});