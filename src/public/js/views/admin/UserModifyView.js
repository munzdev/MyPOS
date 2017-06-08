define(['text!templates/admin/user-modify.phtml'
], function(Template ) {
    "use strict";

    return class UserModifyView extends app.AdminView {

    	events() {
            return {'click #save-btn': 'click_save_btn'};
        }

        initialize(options) {
            this.user = new app.models.User.User();
            this.isNew = options.userid === 'new';
            this.render();

            if (!this.isNew) {
                this.user.set('Userid', options.userid);
                this.fetchData(this.user.fetch());
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
            this.user.save()
                    .done(() => {
                        this.changePage('#admin/user');
                    });
        }
        
        onDataFetched() {
            this.$('#username').val(this.user.get('Username'));
            this.$('#firstname').val(this.user.get('Firstname'));
            this.$('#lastname').val(this.user.get('Lastname'));
            this.$('#phonenumber').val(this.user.get('Phonenumber'));
            this.$('#isAdmin').prop('checked', this.user.get('IsAdmin')).checkboxradio('refresh');
        }

        render() {
            let t = this.i18n();
            
            this.renderTemplate(Template, {isNew: this.isNew});
            
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
                    username: t.errorUsername,
                    firstname: t.errorFirstname,
                    lastname: t.errorLastname,
                    phonenumber: t.errorPhonenumber,
                    password: t.errorPassword,
                    password2: {required: t.errorPassword2Required,
                                equalTo: t.errorPassword2EqualTo}
                }
            });

            this.changePage(this);
        }

    }
} );