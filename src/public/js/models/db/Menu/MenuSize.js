define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class MenuSize extends BaseModel {

        idAttribute() { return 'MenuSizeid'; }

        defaults() {
            return {MenuSizeid: null,
                    Eventid: null,
                    Name: '',
                    Factor: 0};
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