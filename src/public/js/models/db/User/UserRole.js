define([
    
], function(){
    "use strict";

    return class UserRole extends app.BaseModel {
        
        idAttribute() { return 'UserRoleid'; }

        defaults() {
            return {UserRoleid: null,
                    Name: ''};
        }

    }
});