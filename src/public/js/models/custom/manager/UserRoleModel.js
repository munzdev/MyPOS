define(function(){
    "use strict";

    var UserRoleModel = Backbone.Model.extend({

        defaults: {
            events_user_roleid: 0,
            name: ''
        },

    });

    return UserRoleModel;
});