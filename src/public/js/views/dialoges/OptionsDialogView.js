// Options Dialog View
// =============

// Includes file dependencies
define(["app",
        'text!templates/dialoges/options-dialog.phtml'],
function(app, Template )
{
    // Extends Backbone.View
    var OptionsDialogView = Backbone.View.extend( {

    	title: 'options-dialog',
    	el: 'body',
        events: {
        },

        // The View Constructor
        initialize: function(options)
        {
            this.is_admin = options.is_admin;
            this.render();
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