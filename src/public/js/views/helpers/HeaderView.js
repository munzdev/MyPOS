define(["views/HeaderView",
        'text!templates/helpers/navbar.phtml'
], function(HeaderViewBase,
            Template) {
    "use strict";
    
    return class HeaderView extends HeaderViewBase
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