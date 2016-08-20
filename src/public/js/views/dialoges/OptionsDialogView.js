// Options Dialog View
// =============

// Includes file dependencies
define(["app",
        "Webservice",
        'text!templates/dialoges/options-dialog.phtml'],
function(app, Webservice, Template )
{
    // Extends Backbone.View
    var OptionsDialogView = Backbone.View.extend( {

    	title: 'options-dialog',
    	el: 'body',
        events: {
            "click #request-call": 'request_call',
            "click #logout-link": "logout_link"
        },

        // The View Constructor
        initialize: function(options)
        {
            _.bindAll(this, "request_call");

            this.is_admin = options.is_admin;
            this.render();
        },

        request_call: function()
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
        },

        logout_link: function()
        {
            app.session.logout();
        },

        // Renders all of the Category models on the UI
        render: function() {
            MyPOS.RenderPopupTemplate(this, this.title, Template, {is_admin: this.is_admin}, 'data-theme="b"');

            $('#' + this.title).enhanceWithin().popup();

            return this;
        }
    });

    // Returns the View class
    return OptionsDialogView;

} );