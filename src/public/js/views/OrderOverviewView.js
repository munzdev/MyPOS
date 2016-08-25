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
        initialize: function(options) {
            _.bindAll(this, "render",
                            "cancel_order_popup",
                            "cancel_order",
                            "success_popup_close");

            var search = null;

            if(options)
                search = options.search;

            this.ordersList = new OrderOverviewCollection();

            if(search)
                this.ordersList.fetch({data: {search: search},
                                       success: this.render});
            else
                this.ordersList.fetch({success: this.render});
        },

        events: {
            'click .order-overview-cancel-btn': 'cancel_order_popup',
            'click .order-overview-pay-btn': 'click_btn_pay',
            'click .order-overview-info-btn': 'click_btn_info',
            'click .order-overview-modify-btn': 'click_btn_modify',
            'click #order-overview-dialog-continue': 'dialog_continue',
            'click #order-overview-search-btn': 'click_btn_search',
            'click .order-overview-manage-priority-btn': 'click_btn_priority',
            'click .order-overview-manage-price-btn': 'click_btn_price',
            'popupafterclose #order-overview-cancel-success-popup': 'success_popup_close'
        },

        cancel_order_popup: function(event)
        {
            this.cancelOrderId = $(event.currentTarget).attr('data-order-id');
            this.dialogMode = 'cancel';

            $('#order-overview-dialog-title').text("Bestellung stornieren?");
            $('#order-overview-dialog-text').text("Sind sie sicher das die Bestellung storniert werden soll?");
            $('#order-overview-dialog').popup('open');
        },

        dialog_continue: function()
        {
            $('#order-overview-dialog').popup('close')

            if(this.dialogMode == 'cancel')
                this.cancel_order();
            else if(this.dialogMode == 'priority')
                this.set_priority();
        },

        cancel_order: function()
        {
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

        click_btn_pay: function(event)
        {
            var orderid = $(event.currentTarget).attr('data-order-id');
            var tableNr = $(event.currentTarget).attr('data-table-nr');

            MyPOS.ChangePage("#order-pay/id/" + orderid + "/tableNr/" + tableNr);
        },

        click_btn_modify: function(event)
        {
            var orderid = $(event.currentTarget).attr('data-order-id');
            var tableNr = $(event.currentTarget).attr('data-table-nr');

            MyPOS.ChangePage("#order-modify/id/" + orderid + "/tableNr/" + tableNr);
        },

        click_btn_info: function(event)
        {
            var orderid = $(event.currentTarget).attr('data-order-id');

            MyPOS.ChangePage("#order-info/id/" + orderid);
        },

        click_btn_priority: function(event)
        {
            this.priorityOrderId = $(event.currentTarget).attr('data-order-id');
            this.dialogMode = 'priority';

            $('#order-overview-dialog-title').text("Priorität ändern?");
            $('#order-overview-dialog-text').text("Sind sie sicher das die Bestellung vorgereit werden soll?");
            $('#order-overview-dialog').popup('open');
        },

        click_btn_price: function(event)
        {
            var orderid = $(event.currentTarget).attr('data-order-id');

            MyPOS.ChangePage("#order-modify-price/orderid/" + orderid );
        },

        set_priority: function()
        {
            var webservice = new Webservice();
            webservice.action = "Manager/SetPriority";
            webservice.formData = {orderid: this.priorityOrderId};
            webservice.callback = {
                success: function() {
                    MyPOS.ReloadPage();
                }
            };
            webservice.call();
        },

        click_btn_search: function()
        {
            MyPOS.ChangePage("#" + this.title + "/search/");
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