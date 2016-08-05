// Options Dialog View
// =============

// Includes file dependencies
define(["app",
        "Webservice",
        'text!templates/dialoges/messages-dialog.phtml'],
function(app, Webservice, Template )
{
    // Extends Backbone.View
    var OptionsDialogView = Backbone.View.extend( {

    	title: 'messages-dialog',
    	el: 'body',
        events: {
            'click #messages-dialog-send': 'sendMessage'
        },

        // The View Constructor
        initialize: function(options)
        {
            _.bindAll(this, "sendMessage");

            this.render();
        },

        sendMessage: function()
        {
            var message = $('#messages-dialog-message').val();
            $('#messages-dialog-message').val('');

            app.ws.chat.Send(1, message);
        },

        // Renders all of the Category models on the UI
        render: function() {
            MyPOS.RenderPopupTemplate(this, this.title, Template, {}, 'data-theme="b"');

            $('#' + this.title).enhanceWithin().popup();

            return this;
        }
    });

    // Returns the View class
    return OptionsDialogView;

} );