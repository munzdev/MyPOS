// Options Dialog View
// =============

// Includes file dependencies
define(["Webservice",
        'text!templates/dialoges/options-dialog.phtml'],
function(Webservice,
         Template )
{
    "use strict";
    
    return class OptionsDialogView extends app.PopupView
    {
        events() {
            return {"click #admin-link": 'admin_link',
                    "click #request-call": 'request_call',
                    "click #logout-link": "logout_link"};
        }

        // The View Constructor
        initialize(options)
        {
            _.bindAll(this, "request_call");

            this.IsAdmin = options.IsAdmin;
            this.render();
        }

        admin_link()
        {
            this.ChangePage("#admin");
        }

        request_call()
        {
            var self = this;

            var webservice = new Webservice();
            webservice.formData = {reset: false};
            webservice.action = "Users/CallRequest";
            webservice.callback = {
                success: function()
                {
                    $('#' + self.title).popup('close');
                    app.ws.api.Trigger("manager-callback");

                    MyPOS.DisplayError("Rückruf wurde erfolgreich angefordert!");
                }
            };
            webservice.call();
        }
        
        logout_link()
        {
            app.auth.logout();
        }

        // Renders all of the Category models on the UI
        render() {
            this.renderTemplate(Template, {IsAdmin: this.IsAdmin});            

            return this;
        }
    }
} );