define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class EventContact extends BaseModel {

        idAttribute() { return 'EventContactid'; }

        defaults() {
            return {EventContactid: null,
                    Eventid: null,
                    Title: '',
                    Name: '',
                    ContactPerson: '',
                    Address: '',
                    Address2: '',
                    City: '',
                    Zip: '',
                    TaxIdentificationNr: '',
                    Telephon: '',
                    Fax: '',
                    Email: '',
                    Active: false,
                    Default: false};
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