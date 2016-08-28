// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'text!templates/pages/admin/admin.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminView = Backbone.View.extend( {

    	title: 'admin',
    	el: 'body',
        events: {

        },

        // The View Constructor
        initialize: function() {
            //_.bindAll(this, "finished");

            this.render();
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();

            header.activeButton = '';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render()});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminView;

} );