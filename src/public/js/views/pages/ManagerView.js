// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'views/headers/HeaderView',
         'views/footers/ManagerFooterView',
         'text!templates/pages/manager.phtml'],
function( Webservice,
          HeaderView,
          ManagerFooterView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var ManagerView = Backbone.View.extend( {

    	title: 'manager',
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
            var header = new HeaderView();
            var footer = new ManagerFooterView();

            header.activeButton = 'manager';
            footer.activeButton = 'dashboard';

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
    return ManagerView;

} );