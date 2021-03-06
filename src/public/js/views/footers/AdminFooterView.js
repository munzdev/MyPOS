// Login View
// =============

// Includes file dependencies
define(["app",
        'text!templates/footers/admin-footer.phtml'],
 function(app,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminFooterView = Backbone.View.extend( {

    	defaults: {
            activeButton: ''
    	},

        initialize: function(options) {
            this.id = options.id;
        },

        events: {
            'click .admin-footer-link': 'clicked'
        },

        clicked: function(e) {
            e.preventDefault();

            var href = $(e.currentTarget).attr('href');

            MyPOS.ChangePage(href);
        },

        // Renders all of the Category models on the UI
        render: function() {
            var template =  _.template(Template)({activeButton: this.activeButton,
                                                  link: "#admin/event/modify/" + this.id});

            return template;
        }
    } );

    // Returns the View class
    return AdminFooterView;

} );