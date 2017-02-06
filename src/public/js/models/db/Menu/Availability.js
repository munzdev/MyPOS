define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class Availability extends BaseModel {

        idAttribute() { return 'Availabilityid'; }

        defaults() {
            return {Availabilityid: null,
                    Name: ''};
        }

    }
});