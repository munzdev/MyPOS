// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'views/headers/AdminHeaderView',
         'views/footers/AdminFooterView',
         'collections/admin/EventUserCollection',
         'collections/user/UserCollection',
         'collections/manager/UserRoleCollection',
         'text!templates/pages/admin/admin-event-modify-user-modify.phtml'],
function( Webservice,
          AdminHeaderView,
          AdminFooterView,
          EventUserCollection,
          UserCollection,
          UserRoleCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminEventModifyUserModifyView = Backbone.View.extend( {

    	title: 'admin-event-modify-user-modify',
    	el: 'body',
        events: {
            'click #admin-event-modify-user-modify-save-btn': 'click_save_btn'
        },

        click_save_btn: function()
        {
            var self = this;

            var userid = $.trim($('#admin-event-modify-user-modify-userid').val());
            var beginMoney = $.trim($('#admin-event-modify-user-modify-begin-money').val());
            var roles = $("input[name='admin-event-modify-user-modify-role']:checked");

            if(this.mode == 'new' && userid == '')
            {
                MyPOS.DisplayError('Es wurde bereits allen Benutzer für dieses Event eine Berechtigung zugeteilt! Bitte verwenden Sie die Bearbeiten-Funktion!');
                return;
            }

            if(roles.length == 0)
            {
                MyPOS.DisplayError('Bitte eine Berechtigung auswählen!');
                return;
            }

            if(beginMoney == '')
            {
                MyPOS.DisplayError('Bitte eine gültige Wert für das Startgeld eingeben!');
                return;
            }

            var user_roles = 0;

            roles.each(function()
            {
                user_roles += parseInt($(this).val());
            });

            if(this.mode == 'new')
            {
                var webservice = new Webservice();
                webservice.action = "Admin/AddEventUser";
                webservice.formData = {eventid: this.eventid,
                                       userid: userid,
                                       user_roles: user_roles,
                                       begin_money: beginMoney};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/event/modify/' + self.eventid + "/user");
                    }
                };
                webservice.call();
            }
            else
            {
                var webservice = new Webservice();
                webservice.action = "Admin/SetEventUser";
                webservice.formData = {events_userid: this.events_userid,
                                       user_roles: user_roles,
                                       begin_money: beginMoney};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/event/modify/' + self.eventid + "/user");
                    }
                };
                webservice.call();
            }


        },

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render",
                            "click_save_btn");

            var self = this;

            this.eventid = options.id;

            this.beginMoneyValue = "";
            this.rolesValue = {};

            this.existingUserList = new EventUserCollection();

            this.userList = new UserCollection();
            this.userList.url = app.API + "Admin/GetUsersList/";

            this.userRoleList = new UserRoleCollection();

            $.when(this.existingUserList.fetch({data: {eventid: this.eventid}}),
                   this.userList.fetch({data: {eventid: this.eventid}}),
                   this.userRoleList.fetch()).then(function() {
                if(typeof options.events_userid === 'undefined')
                {
                    self.mode = 'new';
                    self.events_userid = 0;
                    self.render();
                }
                else
                {
                    self.mode = 'edit';
                    self.events_userid = options.events_userid;

                    var webservice = new Webservice();
                    webservice.action = "Admin/GetEventUser";
                    webservice.formData = {events_userid: self.events_userid};
                    webservice.callback = {
                        success: function(data)
                        {
                            self.beginMoneyValue = data.begin_money;
                            self.rolesValue = data.user_roles;
                            self.render();
                        }
                    };
                    webservice.call();
                }
            });
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();
            var footer = new AdminFooterView({id: this.eventid});

            header.activeButton = 'event';
            footer.activeButton = 'user';

            var self = this;

            this.userList.remove(this.userList.filter(function(user) {
                return self.existingUserList.findWhere({userid: user.get('userid')});
            }));

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  footer: footer.render(),
                                                                  mode: this.mode,
                                                                  beginMoney: this.beginMoneyValue,
                                                                  roles: parseInt(this.rolesValue),
                                                                  userList: this.userList,
                                                                  userRoleList: this.userRoleList,
                                                                  });

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminEventModifyUserModifyView;

} );