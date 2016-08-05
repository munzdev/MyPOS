define([
    "app"
], function(app){
	"use strict";

    var UserModel = Backbone.Model.extend({

        initialize: function(){
            //_.bindAll(this);
        },

        defaults: {
            userid: 0,
            events_userid: 0,
            username: '',
            firstname: '',
            lastname: '',
            phonenumber: '',
            is_admin: 0,
            user_roles: 0,
            eventid: 0,
            name: '',
            data: ''
        },

    });

    return UserModel;
});