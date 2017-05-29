define(['collections/custom/manager/CallbackCollection',
        'views/helpers/HeaderView',
        'text!templates/manager/callback.phtml',
        'jquery-dateFormat'
], function(CallbackCollection,
            HeaderView,
            Template) {
    "use strict";

    return class CallbackView extends app.ManagerView {

        events() {
            return {'click .done-btn': 'click_btn_done'};
        }

        // The View Constructor
        initialize() {
            this.callbacks = new CallbackCollection();
            this.callbacks.fetch()
                          .done(() => {
                               this.render();
                          });
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

        // Renders all of the Category models on the UI
        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template, {callbacks: this.callbacks});

            this.changePage(this);

            return this;
        }

    }

} );