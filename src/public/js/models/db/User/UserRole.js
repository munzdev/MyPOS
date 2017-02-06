define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class UserRole extends BaseModel {

        idAttribute() { return 'UserRoleid'; }

        defaults() {
            return {UserRoleid: null,
                    Name: ''};
        }

    }
});