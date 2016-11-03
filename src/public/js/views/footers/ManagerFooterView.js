// Login View
// =============

// Includes file dependencies
define(['text!templates/footers/manager-footer.phtml'],
 function(Template ) {
    "use strict";

    // Extends Backbone.View
    var ManagerFooterView = Backbone.View.extend( {

    	defaults: {
            activeButton: ''
    	},
        events: {
            'click .footer-link': 'clicked'
        },

        clicked: function(e) {
            e.preventDefault();
            
            var href = $(e.currentTarget).attr('href');

            MyPOS.ChangePage(href);
        },

        // Renders all of the Category models on the UI
        render: function() {
            var template =  _.template(Template)({activeButton: this.activeButton});

            return template;
        }
    } );

    // Returns the View class
    return ManagerFooterView;

} );