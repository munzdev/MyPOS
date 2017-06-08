define(['text!templates/dialoges/error.phtml'],
function(Template) {
    "use strict";

    return class ErrorDialogView extends app.DialogView
    {
        initialize() {                        
            this.render();
        }
        
        events() {
            return { 'click #close': 'closeDialog' }
    	}
        
        closeDialog(evt)
        {
            evt.preventDefault();
            this.changePage( this.$("#close").attr('data-activePage'), { transition: "flip" });
        }
        
        render() {
            this.renderTemplate(Template);
        }

        // Show alert classes and hide after specified timeout
        showAlert(title, text) {
            this.$("#header").html(title);
            this.$("#content").html(text);
            this.$("#close").attr("data-activePage", $.mobile.activePage.attr('id'));
            this.changePage(this, { transition: "flip" });
        }
    }
} );