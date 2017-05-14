define(['Webservice',
        'collections/custom/manager/CallbackCollection',
        'views/helpers/HeaderView',
        'views/helpers/ManagerFooterView',
        'text!templates/pages/manager-callback.phtml',
        'jquery-dateFormat'],
function( Webservice,
          CallbackCollection,
          HeaderView,
          ManagerFooterView,
          Template ) {
    "use strict";

    return class ManagerCallbackView extends app.PageView {

        events() {
            return {'click .done-btn': 'click_btn_done'};
        }

        // The View Constructor
        initialize() {
            _.bindAll(this, "render");

            this.callbacks = new CallbackCollection();
            this.callbacks.fetch()
                          .done(() => {
                               this.render();
                          });
        }

        click_btn_done(event)
        {
            var userid = $(event.currentTarget).attr('data-user-id');

            var webservice = new Webservice();
            webservice.action = "Manager/ResetCallback";
            webservice.formData = {userid: userid};
            webservice.call()
                      .done(() => {
                          app.ws.chat.SystemMessage(userid, "Ihr Rückruf wurde als erledigt markiert von " + app.session.user.get('firstname') + " " + app.session.user.get('lastname'));
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
            var footer = new ManagerFooterView();
            this.registerSubview(".nav-header", header);
            this.registerSubview(".manager-footer", footer);

            this.renderTemplate(Template, {callbacks: this.callbacks});

            this.changePage(this);

            return this;
        }

    }

} );