// Login View
// =============

// Includes file dependencies
define(['text!templates/helpers/navbar.phtml'],
 function(Template ) {
    "use strict";
    
    return class HeaderView extends app.HeaderView
    {
        events() {
            return {"click #messages": "popupMessages"}
        }
        
        popupMessages(event)
        {
            event.preventDefault();
            $('#' + app.messagesDialog.id()).popup("open", { positionTo: $(event.currentTarget)} );
        }
        
        render() {
            this.renderTemplate(Template, {name: app.auth.authUser.get('Firstname') + " " + app.auth.authUser.get('Lastname'),
                                           unreadedMessages: app.messagesDialog.unreadedMessages});

            return this;
        }
    }
} );