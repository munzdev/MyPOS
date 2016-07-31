// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/HeaderView',
         'collections/distribution/TodoListCollection',
         'models/distribution/DistributionOrderSetModel',
         'models/distribution/OrderDoneInformationModel',
         'models/distribution/ProductsAvailabilitySetModel',
         'text!templates/pages/distribution.phtml',
         "jquery-dateFormat"],
function( app,
          Webservice,
          HeaderView,
          TodoListCollection,
          DistributionOrderSetModel,
          OrderDoneInformationModel,
          ProductsAvailabilitySetModel,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var DistributionView = Backbone.View.extend( {

    	title: 'distribution',
    	el: 'body',
        events: {
            "click .distribution-order": "markOrder",
            "click #distribution-btn-verify-dialog": "showVerifyDialog",
            "click #distribution-finished": "finished",
            "change input[name='distribution-order-details-special-extra-amount']": "ordersDetailsSpecialExtraAmountChanged",
            "change select[name='distribution-order-details-special-extra-status']": "ordersDetailsSpecialExtraAvaibilityChanged"
        },

        // The View Constructor
        initialize: function() {
            var self = this;

            // Broken Tabs widget with Backbone pushstate enabled  - manual fix it
            $(document).on('pagecreate', '#' + this.title, function(createEvent) {
                self.hideTabs();
                $('#distribution-tab-current-order').show();
                $('#distribution-tab-btn-current-order').addClass('ui-btn-active');

                $("#distribution-tabs a[data-role='tab']").click(function(event) {
                    self.hideTabs();
                    $($(event.currentTarget).attr('href')).show();
                });
            });

            var webservice = new Webservice();
            webservice.action = "Distribution/GetDistributionOrderDatas";
            webservice.callback = {
                success: function(result) {
                    self.orderDatas = result;
                    self.orderDatas.GetOrder = new DistributionOrderSetModel(self.orderDatas.GetOrder, {parse: true});
                    self.orderDatas.GetOrdersInTodoList = new TodoListCollection(self.orderDatas.GetOrdersInTodoList, {parse: true});
                    self.orderDatas.GetOrderDoneInformation = new OrderDoneInformationModel(self.orderDatas.GetOrderDoneInformation, {parse: true});
                    self.orderDatas.GetProductsAvailability = new ProductsAvailabilitySetModel(self.orderDatas.GetProductsAvailability, {parse: true});
                    self.render();
                }
            };
            webservice.call();
        },

        ordersDetailsSpecialExtraAvaibilityChanged: function(event)
        {
            alert(2);
        },

        ordersDetailsSpecialExtraAmountChanged: function(event)
        {
            alert(1)  ;
        },

        markOrder: function(event)
        {
            $( event.currentTarget ).toggleClass('green-background');
        },

        finished: function()
        {
            alert("TODO: Implement");
        },

        showVerifyDialog: function()
        {
            var allOrdersMarked = true;

            $('.distribution-order').each(function(index) {
                if(!$(this).hasClass('green-background'))
                {
                    allOrdersMarked = false;
                }
            });

            if(!allOrdersMarked)
            {
                MyPOS.DisplayError('Es müssen zuerst alle Bestellungen markiert werden!');
                return;
            }

            $('#distribution-verify-dialog').popup('open');
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

            var menuesArray = {};

            this.orderDatas.GetProductsAvailability.get('menues').each(function(menu) {
                if(!(menu.get('Group_Name') in menuesArray))
                {
                    menuesArray[menu.get('Group_Name')] = [];
                }

                menuesArray[menu.get('Group_Name')].push(menu);
            });

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  ordersSet: this.orderDatas.GetOrder,
                                                                  ordersInTodoList: this.orderDatas.GetOrdersInTodoList,
                                                                  orderDoneInformation: this.orderDatas.GetOrderDoneInformation,
                                                                  productsAvailability: this.orderDatas.GetProductsAvailability,
                                                                  menuesArray: menuesArray});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return DistributionView;

} );