// Login View
// =============

// Includes file dependencies
define(['text!templates/headers/navbar.phtml'],
 function(Template ) {
    "use strict";
    
    return class HeaderView extends app.HeaderView
    {
        defaults() {
            return {activeButton: ''}
    	}
        
        events() {
            return {"click .header-link": "clicked",
                    "click #options": "popupOpen",
                    "click #messages": "popupOpen"}
        }

        clicked(event) {
            event.preventDefault();

            var href = $(event.currentTarget).attr('href');

            this.ChangePage(href);
        }

        popupOpen(event)
        {
            event.preventDefault();
            var href = $(event.currentTarget).attr('href');

            $(href).popup( "open", { positionTo: $(event.currentTarget)} );
        }

        // Renders all of the Category models on the UI
        render() {
            this.renderTemplate(Template, {name: app.auth.authUser.get('Firstname') + " " + app.auth.authUser.get('Lastname'),
                                           rights: app.auth.authUser.get('EventUser').get('UserRoles'),
                                           activeButton: this.activeButton,
                                           unreadedMessages: app.messagesDialog.unreadedMessages,
                                           is_admin: app.auth.authUser.get('IsAdmin')});

            if(this.$('#navbar ul li', this.$el).length <= 1)
            {
                //template = this.$("<div/>").append(this.$('#navbar', template).remove().end()).html();
            }

            return this;
        }
    }
} );