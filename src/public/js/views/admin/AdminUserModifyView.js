// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'text!templates/pages/admin/admin-user-modify.phtml',
         'jquerymobile-datebox'],
function( app,
          Webservice,
          AdminHeaderView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminUserModifyView = Backbone.View.extend( {

    	title: 'admin-user-modify',
    	el: 'body',
        events: {
            'click #admin-user-modify-save-btn': 'click_save_btn'
        },

        click_save_btn: function()
        {
            var username = $.trim($('#admin-user-modify-username').val());
            var password = $.trim($('#admin-user-modify-password').val());
            var password2 = $.trim($('#admin-user-modify-password2').val());
            var firstname = $.trim($('#admin-user-modify-firstname').val());
            var lastname = $.trim($('#admin-user-modify-lastname').val());
            var phonenumber = $.trim($('#admin-user-modify-phonenumber').val());
            var isAdmin = $('#admin-user-modify-isAdmin').prop('checked');
            var active = $('#admin-user-modify-active').prop('checked');

            if(username == '')
            {
                MyPOS.DisplayError('Bitte einen Benutzername eingeben!');
                return;
            }

            if(this.mode == 'new')
            {
                if(password == '')
                {
                    MyPOS.DisplayError('Bitte ein Passwort eingeben!');
                    return;
                }

                if(password2 == '')
                {
                    MyPOS.DisplayError('Bitte das Passwort wiederhollen!');
                    return;
                }
            }
            else
            {
                if(password != '' && password2 == '')
                {
                    MyPOS.DisplayError('Bitte das Passwort wiederhollen!');
                    return;
                }
            }



            if(password != password2)
            {
                MyPOS.DisplayError('Die Passwörter stimmen nicht überein!');
                return;
            }

            if(firstname == '')
            {
                MyPOS.DisplayError('Bitte einen Vornamen eingeben!');
                return;
            }

            if(lastname == '')
            {
                MyPOS.DisplayError('Bitte einen Nachnamen eingeben!');
                return;
            }

            if(phonenumber == '')
            {
                MyPOS.DisplayError('Bitte eine Telefonnummer eingeben!');
                return;
            }

            if(this.mode == 'new')
            {
                var webservice = new Webservice();
                webservice.action = "Admin/AddUser";
                webservice.formData = {username: username,
                                       password: password,
                                       firstname: firstname,
                                       lastname: lastname,
                                       phonenumber: phonenumber,
                                       isAdmin: isAdmin,
                                       active: active};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/user');
                    }
                };
                webservice.call();
            }
            else
            {
                var webservice = new Webservice();
                webservice.action = "Admin/SetUser";
                webservice.formData = {userid: this.userid,
                                       username: username,
                                       password: password,
                                       firstname: firstname,
                                       lastname: lastname,
                                       phonenumber: phonenumber,
                                       isAdmin: isAdmin,
                                       active: active};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/user');
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

            this.usernameValue = "";
            this.firstnameValue = "";
            this.lastnameValue = "";
            this.phonenumberValue = "";
            this.isAdminValue = 0;
            this.activeValue = 0;

            if(options.id === 'new')
            {
                this.mode = 'new';
                this.userid = 0;
                this.render();
            }
            else
            {
                this.mode = 'edit';
                this.userid = options.id;



                var webservice = new Webservice();
                webservice.action = "Admin/GetUser";
                webservice.formData = {userid: this.userid};
                webservice.callback = {
                    success: function(data)
                    {
                        self.usernameValue = data.username;
                        self.firstnameValue = data.firstname;
                        self.lastnameValue = data.lastname;
                        self.phonenumberValue = data.phonenumber;
                        self.isAdminValue = data.is_admin;
                        self.activeValue = data.active;
                        self.render();
                    }
                };
                webservice.call();
            }
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();

            header.activeButton = 'user';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  mode: this.mode,
                                                                  username: this.usernameValue,
                                                                  firstname: this.firstnameValue,
                                                                  lastname: this.lastnameValue,
                                                                  phonenumber: this.phonenumberValue,
                                                                  isAdmin: this.isAdminValue,
                                                                  active: this.activeValue,
                                                                  });

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminUserModifyView;

} );