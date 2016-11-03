// Login View
// =============

// Includes file dependencies
define(['text!templates/headers/admin.navbar.phtml'],
 function(Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminHeaderView = Backbone.View.extend( {

    	defaults: {
            activeButton: ''
    	},

        events: {
            "click .admin-header-link": "clicked"
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
    return AdminHeaderView;

} );