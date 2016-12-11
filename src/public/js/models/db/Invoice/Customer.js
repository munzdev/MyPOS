define(["models/db/Event/Event"
], function(Event){
    "use strict";

    return class Customer extends app.BaseModel {

        idAttribute() { return 'Customerid'; }

        defaults() {
            return {Customerid: null,
                    Eventid: null,
                    Title: '',
                    Name: '',
                    ContactPerson: '',
                    Adress: '',
                    Adress2: '',
                    City: '',
                    Zip: '',
                    TaxIdentificationNr: '',
                    Telephon: '',
                    Fax: '',
                    Email: '',
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