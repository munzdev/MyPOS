// Login View
// =============

// Includes file dependencies
define([ "app",
         "MyPOS",
         'text!templates/headers/navbar.phtml'],
 function( app,
		 MyPOS,
		 Template ) {
	"use strict";

    // Extends Backbone.View
    var HeaderView = Backbone.View.extend( {


    	defaults: {
    		activeButton: ''
    	},
        events: {
            "click .header-link": "clicked",
            "click #navbar-header-options": "popupOpen",
            "click #navbar-header-messages": "popupOpen"
        },

        clicked: function(e) {
            e.preventDefault();

            var href = $(e.currentTarget).attr('href');

            //this.$el.find("[data-role='navbar'] a.ui-btn-active").removeClass( "ui-btn-active" );
            //$(e.currentTarget).addClass( "ui-btn-active" );

            MyPOS.ChangePage(href);
        },

        popupOpen: function(event)
        {
            event.preventDefault();
            var href = $(event.currentTarget).attr('href');

            $(href).popup( "open", { positionTo: $(event.currentTarget)} );
        },

        // Renders all of the Category models on the UI
        render: function() {

        	return _.template(Template)({name: app.session.user.get('firstname') + " " + app.session.user.get('lastname'),
        								 rights: app.session.user.get('user_roles'),
        								 activeButton: this.activeButton,
        								 is_admin: app.session.user.get('is_admin')});
        }
    } );

    // Returns the View class
    return HeaderView;

} );