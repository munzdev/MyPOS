define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class EventTable extends BaseModel {

        idAttribute() { return 'EventTableid'; }

        defaults() {
            return {EventTableid: null,
                    Eventid: null,
                    Name: '',
                    Data: ''};
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