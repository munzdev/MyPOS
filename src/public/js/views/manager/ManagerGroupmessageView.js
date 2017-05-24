define(['views/helpers/HeaderView',
        'views/helpers/ManagerFooterView',
        'text!templates/manager/manager-groupmessage.phtml'
], function(HeaderView,
            ManagerFooterView,
            Template) {
    "use strict";

    return class ManagerGroupmessageView extends app.PageView {

        events() {
            return {'click #send': 'click_btn_send',
                    'keyup #message': 'onMessageKeyup'};
        }

        // The View Constructor
        initialize() {
            this.userRoleCollection = new app.collections.User.UserRoleCollection();
            this.userRoleCollection.fetch()
                                   .done(() => {
                                       this.render();
                                   });
        }

        onMessageKeyup(evt) {
            var k = evt.keyCode || evt.which;

            if (k == 13 && this.$('#message').val() === ''){
                evt.preventDefault();    // prevent enter-press submit when input is empty
            } else if(k == 13){
                evt.preventDefault();
                this.click_btn_send();
                return false;
            }
        }

        click_btn_send() {
            if (!this.$('#form').valid()) {
                return;
            }

            let message = this.$('#message').val().trim();
            let userRoles = this.$("#userRoles input[name='userRole']:checked");
            let myUserid = app.auth.authUser.get('Userid');
            let sendedUserids = new Set();

            this.$('#message').val('');

            if(message == '')
                return;

            userRoles.each(function(userRole) {
                app.ws.api.Trigger('manager-groupmessage', {callRes: function(data) {

                    $.each(data, function(index, userid) {
                        if (!sendedUserids.has(userid) && userid != myUserid) {
                            sendedUserids.add(userid);
                            app.ws.chat.Send(userid, message);
                            app.messagesDialog.addMessage(userid, message, true, true);
                        }
                    });
                }}, $(this).val());
            });
        }

        // Renders all of the Category models on the UI
        render() {
            let t = this.i18n();
            let header = new HeaderView();
            let footer = new ManagerFooterView();
            this.registerSubview(".nav-header", header);
            this.registerSubview(".manager-footer", footer);

            this.renderTemplate(Template, {userRoleCollection: this.userRoleCollection});

            this.$('#form').validate({
                rules: {
                    message: {required: true},
                    userRole: {required: true}
                },
                messages: {
                    message: t.errorMessage,
                    userRole: t.errorUserRole
                }
            });

            this.changePage(this);

            return this;
        }

    }
} );