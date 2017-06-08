define(['Webservice',
        'models/custom/distributionPlace/DistributionOrderDetail',
        'text!templates/pages/distribution.phtml'
], function(Webservice,
            DistributionOrderDetail,
            Template) {
    "use strict";

    return class DistributionView extends app.PageView {
        initialize() {
            _.bindAll(this, "finished");

            this.distributionOrderDetail = new DistributionOrderDetail();
            this.distributionOrderDetail.fetch()
                                        .done(() => {
                                            app.ws.api.Trigger("distribution-summary-update");
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

            $.mobile.loading("show");

            // TODO use models instead of webservice
            var webservice = new Webservice();
            webservice.action = "DistributionPlace/Amount";
            webservice.formData = {type: type,
                                   id: id,
                                   amount: value};
            webservice.call()
                        .done(() => {
                            // TODO multiple ProductList fetches will be done. Fix this
                            app.productList.fetch()
                                            .done(() => {
                                                $.mobile.loading("hide");
                                                this.reload();
                                            });
                            app.ws.api.Trigger('global:product-update');
                        });
        }

        avaibilityChanged(event) {
            var target = $(event.currentTarget);
            var id = target.attr('data-id');
            var type = target.attr('data-type');
            var value = target.val();

            $.mobile.loading("show");

            // TODO use models instead of webservice
            var webservice = new Webservice();
            webservice.action = "DistributionPlace/Availability";
            webservice.formData = {type: type,
                                   id: id,
                                   status: value};
            webservice.call()
                        .done(() => {
                            // TODO multiple ProductList fetches will be done. Fix this
                            app.productList.fetch()
                                            .done(() => {
                                                $.mobile.loading("hide");
                                                this.reload();
                                            });
                            app.ws.api.Trigger('global:product-update');
                        });
        }

        markOrder(event) {
            $( event.currentTarget ).toggleClass('green-background');
        }

        finished() {
            this.distributionOrderDetail.get('Order').save(null, {url: app.API + 'DistributionPlace',
                                                                  parse: false})
                                                    .done((result) => {
                                                        var webservice = new Webservice();
                                                        webservice.action = "DistributionPlace/Printing/" + result;
                                                        webservice.formData = {EventPrinterid: this.distributionOrderDetail.get('EventPrinterid')};
                                                        webservice.call();                                                                                                                

                                                        this.reload();
                                                    });
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

            this.$('#verify-dialog').popup('open');
        }

        hideTabs() {
            this.$('#tab-order').hide();
            this.$('#tab-avaibility').hide();
        }

        apiCommandReciever(command) {
            if(command == 'distribution-update' && this.distributionOrderDetail.get('Order') == null)
            {
                this.reload();
            }
        }

        render() {
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
        }
    }
} );