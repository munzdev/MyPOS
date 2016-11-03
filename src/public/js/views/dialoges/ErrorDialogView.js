// Login View
// =============

// Includes file dependencies
define(['text!templates/dialoges/error.phtml'],
function(Template ) {
    "use strict";

    return class ErrorDialogView extends app.DialogView
    {
        initialize() {                        
            this.render();
        }
        
        events() {
            return { 'click #close': 'close' }
    	}
        
        close(evt)
        {
            evt.preventDefault();
            $.mobile.changePage( this.$("#close").attr('href'), { transition: "flip" });
        }
        
        render() {
            this.renderTemplate(Template);            

            return this;
        }

        // Show alert classes and hide after specified timeout
        showAlert(title, text) {
            this.$("#header").html(title);
            this.$("#content").html(text);
            this.$("#close").attr("href", '#' + $.mobile.activePage.attr('id'));
            $.mobile.changePage( "#" + this.id(), { transition: "flip" });
        }
    }
} );