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
            return {"click .distribution-order": "markOrder",
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
                    this.reload();
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
                    this.reload();
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

                    this.reload();
                }
            };
            webservice.call();
        }

        showVerifyDialog() {
            var allOrdersMarked = true;

            this.$('.distribution-order').each(function(index) {
                if(!$(this).hasClass('green-background'))
                {
                    allOrdersMarked = false;
                }
            });

            if(!allOrdersMarked)
            {
                app.error.showAlert('Fehler!', 'Es müssen zuerst alle Bestellungen markiert werden!');
                return;
            }

            $('#verify-dialog').popup('open');
        }

        hideTabs() {
            this.$('#tab-order').hide();
            this.$('#tab-avaibility').hide();
        }

        apiCommandReciever(command) {
            if(command == 'update' && this.distributionOrderDetail.get('Order') == null)
            {
                this.reload();
            }
        }

        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template, {distributionOrderDetail: this.distributionOrderDetail,
                                           products: app.productList});

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