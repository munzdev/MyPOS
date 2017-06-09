define(['text!templates/manager/groupmessage.phtml',
        'text!templates/manager/groupmessage-item.phtml',
], function(Template,
            TemplateItem) {
    "use strict";

    return class GroupmessageView extends app.ManagerView {

        events() {
            return {'click #send': 'click_btn_send',
                    'keyup #message': 'onMessageKeyup'};
        }

        initialize() {
            this.userRoleCollection = new app.collections.User.UserRoleCollection();
            this.refresh();
        }

        refresh() {
            let i18n = this.i18n();
            this.$('#userRoles-list').empty();

            if(!this.rendered) {
                this.render();
                this.rendered = true;
            }

            this.fetchData(this.userRoleCollection.fetch(), i18n.loading);
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

        onDataFetched() {
            let template = _.template(TemplateItem);

            this.userRoleCollection.each((userRole) => {
                this.$('#userRoles-list').append(template({userRole: userRole}));
            });

            this.$el.trigger('create');
        }

        render() {
            let t = this.i18n();
            this.renderTemplate(Template);

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
        }

    }
} );