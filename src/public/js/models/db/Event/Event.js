define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class Event extends BaseModel {

        idAttribute() { return 'Eventid'; }

        defaults() {
            return {Eventid: null,
                    Name: '',
                    Date: '',
                    Active: false};
        }

    }
});