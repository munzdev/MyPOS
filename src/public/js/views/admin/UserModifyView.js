define(['views/helpers/HeaderView',
        'text!templates/admin/user-modify.phtml'
], function(HeaderView,
            Template ) {
    "use strict";

    return class UserModifyView extends app.AdminView {

    	events() {
            return {'click #save-btn': 'click_save_btn'};
        }

        initialize(options) {
            this.user = new app.models.User.User();

            if(options.userid === 'new') {
                this.render();
            } else {
                this.user.set('Userid', options.userid);
                this.user.fetch()
                        .done(() => {
                           this.render();
                        });
            }
        }

        click_save_btn() {
            if (!this.$('#form').valid()) {
                return;
            }
            
            this.user.set('Username', $.trim(this.$('#username').val()));
            this.user.set('Password', $.trim(this.$('#password').val()));
            this.user.set('Firstname', $.trim(this.$('#firstname').val()));
            this.user.set('Lastname', $.trim(this.$('#lastname').val()));
            this.user.set('Phonenumber', $.trim(this.$('#phonenumber').val()));
            this.user.set('IsAdmin', this.$('#isAdmin').prop('checked'));
            this.user.set('Active', this.$('#active').prop('checked'));
            this.user.save()
                    .done(() => {
                        this.changePage('#admin/user');
                    });
        }

        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template, {user: this.user});
            
            this.$('#form').validate({
                rules: {
                    username: {required: true},
                    firstname: {required: true},
                    lastname: {required: true},
                    phonenumber: {required: true},
                    password: {required: this.user.isNew()},
                    password2: {required: this.user.isNew(),
                                equalTo: '#password'}
                },
                messages: {
                    username: "Bitte einen Benutzername eingeben!",
                    firstname: 'Bitte einen Vornamen eingeben!',
                    lastname: "Bitte einen Nachnamen eingeben!",
                    phonenumber: "Bitte eine Telefonnummer eingeben!",
                    password: "Bitte ein Passwort eingeben!",
                    password2: {required: 'Bitte das Passwort wiederhollen!',
                                equalTo: 'Die Passwörter stimmen nicht überein!'}
                }
            });

            this.changePage(this);

            return this;
        }

    }
} );