// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'views/headers/HeaderView',
         'collections/distribution/TodoListCollection',
         'models/distribution/DistributionOrderSetModel',
         'models/distribution/OrderDoneInformationModel',
         'models/distribution/ProductsAvailabilitySetModel',
         'text!templates/pages/distribution.phtml',
         "jquery-dateFormat"],
function( Webservice,
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
            "change input[name='distribution-order-details-special-extra-amount']": "amountChanged",
            "change select[name='distribution-order-details-special-extra-status']": "avaibilityChanged",
            "change #distribution-availability-menues-list input": "amountChanged",
            "change #distribution-availability-menues-list select": "avaibilityChanged",
            "change #distribution-availability-extras-list input": "amountChanged",
            "change #distribution-availability-extras-list select": "avaibilityChanged",
            "change #distribution-availability-special-extras-list input": "amountChanged",
            "change #distribution-availability-special-extras-list select": "avaibilityChanged"
        },

        // The View Constructor
        initialize: function() {
            _.bindAll(this, "finished");


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

        amountChanged: function(event)
        {
            var target = $(event.currentTarget);
            var id = target.attr('data-id');
            var type = target.attr('data-type');
            var value = target.val();

            if(value == '' || value < 0)
                value = 0;

            var webservice = new Webservice();
            webservice.action = "Distribution/SetAvailabilityAmount";
            webservice.formData = {type: type,
                                   id: id,
                                   amount: value};
            webservice.callback = {
                success: function(){
                    app.ws.api.Trigger('global:product-update');
                    MyPOS.ReloadPage();
                }
            };
            webservice.call();
        },

        avaibilityChanged: function(event)
        {
            var target = $(event.currentTarget);
            var id = target.attr('data-id');
            var type = target.attr('data-type');
            var value = target.val();

            var webservice = new Webservice();
            webservice.action = "Distribution/SetAvailabilityStatus";
            webservice.formData = {type: type,
                                   id: id,
                                   status: value};
            webservice.callback = {
                success: function(){
                    app.ws.api.Trigger('global:product-update');
                    MyPOS.ReloadPage();
                }
            };
            webservice.call();
        },

        markOrder: function(event)
        {
            $( event.currentTarget ).toggleClass('green-background');
        },

        finished: function()
        {
            var self = this;

            var orders_finished = {order_in_progressids: this.orderDatas.GetOrder.get('orders_in_progressids'),
                                   order_details: [],
                                   order_details_special_extras: []};

            $('.distribution-order').each(function() {
                var type = $(this).attr('data-order-type');

                if(type == 'order-detail')
                {
                    orders_finished.order_details.push({id: $(this).attr('data-id'),
                                                        amount: $(this).attr('data-amount')});
                }
                else
                {
                    orders_finished.order_details_special_extras.push({id: $(this).attr('data-id'),
                                                                       amount: $(this).attr('data-amount')});
                }
            });

            var webservice = new Webservice();
            webservice.action = "Distribution/FinishOrder";
            webservice.formData = orders_finished;
            webservice.callback = {
                success: function(result) {
                    var webservice = new Webservice();
                    webservice.action = "Distribution/PrintOrder";
                    webservice.formData = {distributions_giving_outid: result,
                                           events_printerid: self.orderDatas.GetOrder.get('events_printerid')};
                    webservice.call();

                    MyPOS.ReloadPage();
                }
            };
            webservice.call();
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

        apiCommandReciever: function(command)
        {
            if(command == 'distribution-update' && this.orderDatas.GetOrder.get('orders_details').length == 0)
            {
                MyPOS.ReloadPage();
            }
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