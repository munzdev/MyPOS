define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class EventPrinter extends BaseModel {

        idAttribute() { return 'EventPrinterid'; }

        defaults() {
            return {EventPrinterid: null,
                    Eventid: null,
                    Name: '',
                    Type: 0,
                    Attr1: '',
                    Attr2: '',
                    Default: false,
                    CharactersPerRow: 0};
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