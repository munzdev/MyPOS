define([
    
], function(){
    "use strict";

    return class Availability extends app.BaseModel {
        
        idAttribute() { return 'Availabilityid'; }

        defaults() {
            return {Availabilityid: null,
                    Name: ''};
        }

    }
});