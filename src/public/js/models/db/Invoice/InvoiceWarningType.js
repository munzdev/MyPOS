define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class InvoiceWarningType extends BaseModel {

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
                response.Event = new app.models.Event.Event(response.Event, {parse: true});
            }

            return super.parse(response);
        }
    }
});