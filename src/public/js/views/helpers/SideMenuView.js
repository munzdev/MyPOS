define(['text!templates/helpers/side-menu.phtml',
        'Webservice'
], function(Template,
            Webservice) {
    "use strict";
    
    return class SideMenuView extends app.PanelView
    {
        initialize() {
            _.bindAll(this, "open",
                            "clicked");
                            
            this.render();
        }
        
        defaults() {
            return {activeButton: ''}
    	}
        
        events() {
            return {"click #callRequest": "callRequest",
                    "click #logout": "logout",
                    "click .header-link": "clicked"};
        }
        
        clicked(event) {
            event.preventDefault();

            var href = $(event.currentTarget).attr('href');

            this.changeHash(href);
        }        

        callRequest()
        {
            let i18n = this.i18n();

            var webservice = new Webservice();
            webservice.action = "User/CallRequest";
            webservice.call().done(() => {
                this.$el.panel("close");
                app.ws.api.Trigger("manager-callback");
                app.error.showAlert(i18n.callRequestSuccessfully, i18n.callRequestDone);
            });
        }
        
        logout()
        {
            app.auth.logout();
        }

        open() {
            this.$el.panel( "open");
        }

        render() {
            this.renderTemplate(Template, {activeButton: this.activeButton,
                                           rights: app.auth.authUser.get('EventUser').get('UserRoles'),
                                           unreadedMessages: app.messagesDialog.unreadedMessages,
                                           isAdmin: app.auth.authUser.get('IsAdmin')});
        }
    }
} );