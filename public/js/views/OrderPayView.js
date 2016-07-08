/* global _, Backbone, parseFloat */

// Login View
// =============

// Includes file dependencies
define([ "app",
         "MyPOS",
         'models/order/payments/PaymentModel',
         'collections/PrinterCollection',
         'views/headers/HeaderView',
         'text!templates/pages/order-pay.phtml'],
function(app,
         MyPOS,
         PaymentModel,
         PrinterCollection,
         HeaderView,
         Template) {
    "use strict";

    // Extends Backbone.View
    var OrderPayView = Backbone.View.extend( {

    	title: 'order-pay',
    	el: 'body',

    	events: {

    	},

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render");

            this.id = options.id;

            this.payments = new PaymentModel();

            this.payments.fetch({data: {orderid: this.id},
                                 success: this.render});
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();
            header.activeButton = 'order-overview';


            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  printers: new PrinterCollection/* TEMP!! */,
                                                                  payments: this.payments,
                                                                  products: app.session.products});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }
    });

    return OrderPayView;
} );