// Login View
// =============

// Includes file dependencies
define([ "app",
         'views/headers/HeaderView',
         'text!templates/pages/order-overview-search.phtml',
         'jquerymobile-datebox'],
 function(  app,
            HeaderView,
            Template ) {
    "use strict";

    // Extends Backbone.View
    var OrderOverviewSearchView = Backbone.View.extend( {

    	title: 'order-overview-search',
    	el: 'body',

        // The View Constructor
        initialize: function() {
            this.searchStatus = 'all';

            this.render();
        },

        events: {
            'click #order-overview-search-footer-back': 'click_btn_back',
            'click #order-overview-search-status a': 'click_btn_status'
        },

        click_btn_back: function(event)
        {
            event.preventDefault();
            window.history.back();
        },

        click_btn_status: function(event)
        {
            $('#order-overview-search-status a').removeClass('ui-btn-active');
            $(event.currentTarget).addClass('ui-btn-active');
            this.searchStatus = $(event.currentTarget).attr('data-value');
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
    return OrderOverviewSearchView;

} );