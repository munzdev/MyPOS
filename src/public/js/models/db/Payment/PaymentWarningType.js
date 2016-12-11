define(["models/db/Event/Event"
], function(Event){
    "use strict";

    return class PaymentWarningType extends app.BaseModel {

        idAttribute() { return 'PaymentWarningTypeid'; }

        defaults() {
            return {PaymentWarningTypeid: null,
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