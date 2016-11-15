// Login Vew
// =============

// Includes file dependencies
define(['text!templates/headers/side-menu.phtml',
        'Webservice'],
 function(Template,
          Webservice) {
    "use strict";
    
    return class SideMenuView extends app.HeaderView
    {
        initialize(options) {
            _.bindAll(this, "swiperight",
                            "clicked");
        }
        
        defaults() {
            return {activeButton: ''}
    	}
        
        events() {
            return {"swiperight": "swiperight",
                    "click #callRequest": "callRequest",
                    "click #logout": "logout",
                    "click .header-link": "clicked"}
        }
        
        clicked(event) {
            event.preventDefault();

            var href = $(event.currentTarget).attr('href');

            this.ChangePage(href);
        }
        
        
        callRequest()
        {
            var webservice = new Webservice();
            webservice.action = "Users/CallRequest";
            webservice.call().done(() => {
                $( "#side-menu" ).panel( "close")
                app.ws.api.Trigger("manager-callback");
                app.error.showAlert("Rückruf wurde erfolgreich angefordert!");
            });
        }
        
        logout()
        {
            app.auth.logout();
        }
        
        setElement(element) {            
            super.setElement(element);           
            if(element instanceof $ == false) return;            
            element.find('.side-menu-open').click(this.swiperight);
        }

        swiperight() {
            this.$( "#side-menu" ).panel( "open");
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