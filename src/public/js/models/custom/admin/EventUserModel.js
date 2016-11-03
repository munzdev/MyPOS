define(function(){
    "use strict";

    var EventUserModel = Backbone.Model.extend({

        defaults: {
            events_userid: 0,
            userid: 0,
            username: '',
            name: '',
            user_roles: 0,
            roles: '',
            begin_money: 0
        },
    });

    return EventUserModel;
});