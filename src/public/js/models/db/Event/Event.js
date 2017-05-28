define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class Event extends BaseModel {

        idAttribute() { return 'Eventid'; }
        urlRoot() {return app.API + "DB/Event";}

        defaults() {
            return {Eventid: null,
                    Name: '',
                    Date: '',
                    Active: false};
        }

    }
});