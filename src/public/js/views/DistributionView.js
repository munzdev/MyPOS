// Login View
// =============

// Includes file dependencies
define([ "app",
         'views/headers/HeaderView',
         'models/distribution/DistributionOrderSetModel',
         'text!templates/pages/distribution.phtml'],
function( app, HeaderView, DistributionOrderSetModel, Template ) {
    "use strict";

    // Extends Backbone.View
    var DistributionView = Backbone.View.extend( {

    	title: 'distribution',
    	el: 'body',
        events: {
            "click #distribution-current-menu div": "markOrder"
        },

        // The View Constructor
        initialize: function() {
            var self = this;

            // Broken Tabs widget with Backbone pushstate enabled  - manual fix it
            $(document).on('pagecreate', '#' + this.title, function(createEvent) {
                self.hideTabs();

                $("#distribution-tabs a[data-role='tab']").click(function(event) {
                    self.hideTabs();
                    $($(event.currentTarget).attr('href')).show();
                });
            });

            this.orderSet = new DistributionOrderSetModel();
            this.orderSet.fetch({
                success: function() {
                    self.render();
                }
            });
        },

        markOrder: function(event)
        {
            $( event.currentTarget ).toggleClass('green-background');
        },

        hideTabs: function()
        {
            $('#distribution-tab-current-order').hide();
            $('#distribution-tab-set-avaibility').hide();
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();

            header.activeButton = 'distribution';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  ordersSet: this.orderSet,
                                                                  products: [],
                                                                  nextOrders: [],
                                                                  amountOpenOrders: 12,
                                                                  amountFinishedOrders: 5,
                                                                  amountNewOrders: 8});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return DistributionView;

} );