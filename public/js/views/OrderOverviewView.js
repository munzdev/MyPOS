// Login View
// =============

// Includes file dependencies
define([ "app",
         "MyPOS",
         "Webservice",
         'collections/OrderOverviewCollection',
         'views/headers/HeaderView',
         'text!templates/pages/order-overview.phtml'],
 function(  app,
            MyPOS,
            Webservice,
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
            _.bindAll(this, "render",
                            "cancel_order_popup",
                            "cancel_order",
                            "success_popup_close");

            this.ordersList = new OrderOverviewCollection();

            this.ordersList.fetch({success: this.render});
        },

        events: {
            'click .order-overview-cancel-btn': 'cancel_order_popup',
            'click #order-overview-cancel-order-dialog-continue': 'cancel_order',
            'popupafterclose #order-overview-cancel-success-popup': 'success_popup_close'
        },

        cancel_order_popup: function(event)
        {
            this.cancelOrderId = $(event.currentTarget).attr('data-order-id');

            $('#order-overview-cancel-order-dialog').popup('open');
        },

        cancel_order: function()
        {
            $('#order-overview-cancel-order-dialog').popup('close')

            var webservice = new Webservice();
            webservice.action = "Orders/MakeCancel";
            webservice.formData = {orderid: this.cancelOrderId};

            webservice.callback = {
                success: function() {
                    $('#order-overview-cancel-success-popup').popup("open");
                }
            };
            webservice.call();
        },

        success_popup_close: function()
        {
            Backbone.history.loadUrl();
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
        }
    } );

    // Returns the View class
    return OrderOverviewView;

} );