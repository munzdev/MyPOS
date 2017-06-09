define(['collections/custom/manager/CallbackCollection',
        'text!templates/manager/callback.phtml',
        'text!templates/manager/callback-item.phtml'
], function(CallbackCollection,
            Template,
            TemplateItem) {
    "use strict";

    return class CallbackView extends app.ManagerView {

        events() {
            return {'click .done-btn': 'click_btn_done'};
        }

        initialize() {
            this.callbacks = new CallbackCollection();
            this.refresh();
        }

        refresh() {
            let i18n = this.i18n();
            this.$('#callbacks-list').empty();

            if(!this.rendered) {
                this.render();
                this.rendered = true;
            }

            this.fetchData(this.callbacks.fetch(), i18n.loading);
        }

        click_btn_done(event)
        {
            var cid = $(event.currentTarget).attr('data-user-cid');
            var i18n = this.i18n();

            var user = this.callbacks.get({cid: cid});
            user.set('CallRequest', null);
            user.save()
                .done(() => {
                    app.ws.chat.SystemMessage(user.get('Userid'), sprintf(i18n.systemMessage, {name: app.auth.authUser.get('Firstname') + " " + app.auth.authUser.get('Lastname')}));
                    this.reload();
                });
        }

        apiCommandReciever(command)
        {
            if(command == 'manager-callback')
            {
                this.reload();
            }
        }

        onDataFetched() {
            let template = _.template(TemplateItem);
            let i18n = this.i18n();

            this.callbacks.each((callback) => {
                this.$('#callbacks-list').append(template({callback: callback,
                    t: i18n}));
            });
        }

        render() {
            this.renderTemplate(Template);

            this.changePage(this);
        }

    }

} );