define(["models/db/Event/Event"
], function(Event){
    "use strict";

    return class InvoiceWarningType extends app.BaseModel {

        idAttribute() { return 'InvoiceWarningTypeid'; }

        defaults() {
            return {InvoiceWarningTypeid: null,
                    Eventid: null,
                    Name: '',
                    ExtraPrice: 0};
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