// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'views/footers/AdminFooterView',
         'text!templates/pages/admin/admin-event-modify.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          AdminFooterView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminEventView = Backbone.View.extend( {

    	title: 'admin-event',
    	el: 'body',
        events: {

        },

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render");

            this.id = options.id;

            this.render();
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();
            var footer = new AdminFooterView({id: this.id});

            header.activeButton = 'event';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  footer: footer.render()});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminEventView;

} );