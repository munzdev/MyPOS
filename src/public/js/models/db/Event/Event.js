define([
    
], function(){
    "use strict";

    return class Event extends app.BaseModel {
        
        idAttribute() { return 'Eventid'; }

        defaults() {
            return {Eventid: null,
                    Name: '',
                    Date: '',
                    Active: false};
        }

    }
});