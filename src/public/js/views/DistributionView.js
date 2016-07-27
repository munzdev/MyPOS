// Login View
// =============

// Includes file dependencies
define([ "app",
         'views/headers/HeaderView',
         'text!templates/pages/distribution.phtml'],
function( app, HeaderView, Template ) {
    "use strict";

    // Extends Backbone.View
    var DistributionView = Backbone.View.extend( {

    	title: 'distribution',
    	el: 'body',
        events: {
            "click #distribution-current-menu div": "markOrder"
        },

        markOrder: function(event)
        {
            $( event.currentTarget ).toggleClass('green-background');
        },

        // The View Constructor
        initialize: function() {
            this.render();
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();

            header.activeButton = 'distribution';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render()});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return DistributionView;

} );