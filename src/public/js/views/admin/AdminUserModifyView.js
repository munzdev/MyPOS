define(['views/helpers/HeaderView',
        'text!templates/admin/admin-user-modify.phtml'
], function(HeaderView,
            Template ) {
    "use strict";

    return class AdminUserModifyView extends app.PageView {

    	events() {
            return {'click #save-btn': 'click_save_btn'}
        }

        initialize(options) {
            this.usernameValue = "";
            this.firstnameValue = "";
            this.lastnameValue = "";
            this.phonenumberValue = "";
            this.isAdminValue = 0;
            this.activeValue = 0;

            if(options.userid === 'new') {
                this.mode = 'new';
                this.userid = 0;
                this.render();
            } else {
                this.mode = 'edit';
                this.userid = options.userid;

                let userCollection = new app.collections.User.UserCollection();
                this.user = new app.models.User.User();
                this.user.fetch({url: userCollection.url() + '/' + this.userid})
                        .done((user) => {
                           this.usernameValue = this.user.get('Username');
                           this.firstnameValue = this.user.get('Firstname');
                           this.lastnameValue = this.user.get('Lastname');
                           this.phonenumberValue = this.user.get('Phonenumber');
                           this.isAdminValue = this.user.get('IsAdmin');
                           this.activeValue = this.user.get('Active');
                           this.render();
                        });
            }
        }

        click_save_btn() {
            var username = $.trim($('#username').val());
            var password = $.trim($('#password').val());
            var password2 = $.trim($('#password2').val());
            var firstname = $.trim($('#firstname').val());
            var lastname = $.trim($('#lastname').val());
            var phonenumber = $.trim($('#phonenumber').val());
            var isAdmin = $('#isAdmin').prop('checked');
            var active = $('#active').prop('checked');

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


        }

        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template, {mode: this.mode,
                                           username: this.usernameValue,
                                           firstname: this.firstnameValue,
                                           lastname: this.lastnameValue,
                                           phonenumber: this.phonenumberValue,
                                           isAdmin: this.isAdminValue,
                                           active: this.activeValue});

            this.changePage(this);

            return this;
        }

    }
} );