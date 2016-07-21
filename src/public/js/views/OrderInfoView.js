// Login View
// =============

// Includes file dependencies
define([ "app",
         "MyPOS",
         "Webservice",
         'views/headers/HeaderView',
         'text!templates/pages/order-info.phtml'],
 function(  app,
            MyPOS,
            Webservice,
            HeaderView,
            Template ) {
    "use strict";

    // Extends Backbone.View
    var OrderInfoView = Backbone.View.extend( {

    	title: 'order-info',
    	el: 'body',

        // The View Constructor
        initialize: function() {
            _.bindAll(this, "render");
            this.render();
        },

        events: {

        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();

            header.activeButton = 'order-overview';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render()});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }
    } );

    // Returns the View class
    return OrderInfoView;

} );