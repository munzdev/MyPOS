define([
    "app"
], function(app){
    "use strict";

    return class UserRole extends Backbone.Model {
        
        idAttribute() { return 'UserRoleid'; }

        defaults() {
            return {UserRoleid: 0,
                    Name: ''};
        }

    }
});