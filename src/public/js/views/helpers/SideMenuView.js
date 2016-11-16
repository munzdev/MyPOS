// Login Vew
// =============

// Includes file dependencies
define(['text!templates/helpers/side-menu.phtml',
        'Webservice'],
 function(Template,
          Webservice) {
    "use strict";
    
    return class SideMenuView extends app.PanelView
    {
        initialize(options) {
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
                    "click .header-link": "clicked"}
        }
        
        clicked(event) {
            event.preventDefault();

            var href = $(event.currentTarget).attr('href');

            this.changeHash(href);
        }        
        
        callRequest()
        {
            var webservice = new Webservice();
            webservice.action = "Users/CallRequest";
            webservice.call().done(() => {
                this.$el.panel("close");
                app.ws.api.Trigger("manager-callback");
                app.error.showAlert("Rückruf wurde erfolgreich angefordert!");
            });
        }
        
        logout()
        {
            app.auth.logout();
        }

        open() {
            this.$el.panel( "open");
        }

        // Renders all of the Category models on the UI
        render() {
            this.renderTemplate(Template, {activeButton: this.activeButton,
                                           rights: app.auth.authUser.get('EventUser').get('UserRoles'),
                                           unreadedMessages: app.messagesDialog.unreadedMessages,
                                           isAdmin: app.auth.authUser.get('IsAdmin')});
            return this;
        }
    }
} );