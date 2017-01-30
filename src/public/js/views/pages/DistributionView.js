define(['Webservice',
        'views/helpers/HeaderView',
        'models/custom/distributionPlace/DistributionOrderDetail',
        'text!templates/pages/distribution.phtml'
], function(Webservice,
            HeaderView,
            DistributionOrderDetail,
            Template) {
    "use strict";

    return class DistributionView extends app.PageView {
        initialize() {
            _.bindAll(this, "finished");

            this.distributionOrderDetail = new DistributionOrderDetail();
            this.distributionOrderDetail.fetch()
                                        .done(() => {
                                            this.render();
                                        });
        }

        events() {
            return {"click .order": "markOrder",
                    "click #btn-verify-dialog": "showVerifyDialog",
                    "click #finished": "finished",
                    "change input[name='order-details-special-extra-amount']": "amountChanged",
                    "change select[name='order-details-special-extra-status']": "avaibilityChanged",
                    "change #availability-menues-list input": "amountChanged",
                    "change #availability-menues-list select": "avaibilityChanged",
                    "change #availability-extras-list input": "amountChanged",
                    "change #availability-extras-list select": "avaibilityChanged",
                    "change #availability-special-extras-list input": "amountChanged",
                    "change #availability-special-extras-list select": "avaibilityChanged"}
        }

        amountChanged(event) {
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
        }

        avaibilityChanged(event) {
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
        }

        markOrder(event) {
            $( event.currentTarget ).toggleClass('green-background');
        }

        finished() {
            var self = this;

            var orders_finished = {order_in_progressids: this.orderDatas.GetOrder.get('orders_in_progressids'),
                                   order_details: [],
                                   order_details_special_extras: []};

            $('.order').each(function() {
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
        }

        showVerifyDialog() {
            var allOrdersMarked = true;

            $('.order').each(function(index) {
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

            $('#verify-dialog').popup('open');
        }

        hideTabs() {
            this.$('#tab-order').hide();
            this.$('#tab-avaibility').hide();
        }

        apiCommandReciever(command) {
            if(command == 'update' && this.orderDatas.GetOrder.get('orders_details').length == 0)
            {
                MyPOS.ReloadPage();
            }
        }

        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            var menuesArray = {};

            /*this.orderDatas.GetProductsAvailability.get('menues').each(function(menu) {
                if(!(menu.get('Group_Name') in menuesArray))
                {
                    menuesArray[menu.get('Group_Name')] = [];
                }

                menuesArray[menu.get('Group_Name')].push(menu);
            });*/

            this.renderTemplate(Template, {distributionOrderDetail: this.distributionOrderDetail,
                                           ordersSet: /*this.orderDatas.GetOrder*/ new Backbone.Model(),
                                           ordersInTodoList: /*this.orderDatas.GetOrdersInTodoList*/ new Backbone.Collection(),
                                           orderDoneInformation: /*this.orderDatas.GetOrderDoneInformation*/ new Backbone.Model(),
                                           productsAvailability: /*this.orderDatas.GetProductsAvailability*/ new Backbone.Model({extras: new Backbone.Collection(),
                                                                                                                                 special_extras: new Backbone.Collection()}),
                                           MyPOSConfig: {Distribution: {}},
                                           menuesArray: menuesArray});

            // Broken Tabs widget with Backbone pushstate enabled  - manual fix it
            this.hideTabs();
            this.$('#tab-order').show();
            this.$("#tabs a[data-role='tab']").click((event) => {
                this.hideTabs();
                this.$($(event.currentTarget).attr('href')).show();
            });

            this.changePage(this);

            return this;
        }
    }
} );