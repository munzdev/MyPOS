// Login View
// =============

// Includes file dependencies
define([ "app",
         "MyPOS",
         'collections/OrderOverviewCollection',
         'views/headers/HeaderView',
         'text!templates/pages/order-overview.phtml'],
 function( app,
		 MyPOS,
		 OrderOverviewCollection,
		 HeaderView,
		 Template ) {
	"use strict";

    // Extends Backbone.View
    var OrderOverviewView = Backbone.View.extend( {

    	title: 'order-overview',
    	el: 'body',


        // The View Constructor
        initialize: function() {
        	_.bindAll(this, "render");

        	this.ordersList = new OrderOverviewCollection();

        	this.ordersList.fetch({success: this.render});
        },

        // Renders all of the Category models on the UI
        render: function() {
        	var header = new HeaderView();

        	header.activeButton = 'order-overview';

        	MyPOS.RenderPageTemplate(this, this.title, Template, {orders: this.ordersList,
        														  header: header.render()});

        	this.setElement("#" + this.title);
        	header.setElement("#" + this.title + " .nav-header");

        	$.mobile.changePage( "#" + this.title);
        	return this;
        },
    } );

    // Returns the View class
    return OrderOverviewView;

} );