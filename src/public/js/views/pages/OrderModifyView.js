define(["models/custom/order/OrderModify",
        "models/db/Ordering/OrderDetail",
        'views/helpers/OrderItemsView',
        'text!templates/pages/order-modify.phtml',
        'text!templates/pages/order-modify-panel.phtml'
], function(OrderModify,
            OrderDetail,
            OrderItemsView,
            Template,
            TemplatePanel) {
    "use strict";

    // Extends Backbone.View
    return class OrderModifyView extends app.PageView
    {
        events() {
            return {'panelbeforeclose #panel-special': 'order_special_close',

                    // Manually handle events. Ensures that the close event is called after the task
                    'click #panel-close': 'order_close',
                    'click #panel-add': 'order_add',
                    'click #panel-mixing': 'order_mixing',
                    'click .menu-item': 'menu_item',
                    'click #panel-special-add': 'order_special_add',
                    'click #footer-back': 'footer_back',
                    'click #footer-finish': 'footer_finish',
                    'click #finished': 'finished'}
    	}

        // The View Constructor
        initialize(options) {
            this.options = options;
            this.isMixing = null;

            this.orderModify = new OrderModify();
            this.orderItemsView = new OrderItemsView({mode: 'modify',
                                                      skipCounts: false,
                                                      statusInformation: false,
                                                      countCallback: this.renderOrder.bind(this)});

            if(options.orderid === null) {
                this.mode = 'new';
                this.orderModify.set('EventTable', new app.models.Event.EventTable({Name: options.tableNr}));
                this.render();
            } else {
                this.mode = 'edit';

                $.mobile.loading("show");

                this.orderModify.set('Orderid', options.orderid);
                this.orderModify.fetch()
                                .done(() => {
                                    $.mobile.loading("hide");
                                    this.render();
                                    this.renderOrder();
                                    this.showOverview();
                                });
            }
        }

        hideTabs() {
            app.productList.each((type) => {
                this.$('#' + type.get('MenuTypeid')).hide();
            });
            this.$('#overview').hide();
        }

        order_add(event) {
            let menuid = this.currentMenuid;

            let menuSearch = _.find(app.productList.searchHelper, function(obj) {return obj.Menuid == menuid});

            let orderDetail = new OrderDetail();
            orderDetail.set("OrderDetailExtras", new app.collections.Ordering.OrderDetailExtraCollection());
            orderDetail.set("OrderDetailMixedWiths", new app.collections.Ordering.OrderDetailMixedWithCollection());

            let extrasSelected = this.$('input[name=extra-' + menuid + ']:checked');

            extrasSelected.each(function(index, extra){

                let menuPossibleExtra = app.productList.findWhere({MenuTypeid: menuSearch.MenuTypeid})
                                                .get('MenuGroup')
                                                .findWhere({MenuGroupid: menuSearch.MenuGroupid})
                                                .get('Menu')
                                                .findWhere({Menuid: menuid})
                                                .get('MenuPossibleExtra')
                                                .findWhere({MenuExtraid: parseInt($(extra).val())});

                let extraModel = new (orderDetail.get("OrderDetailExtras").model);
                extraModel.set("MenuPossibleExtraid", menuPossibleExtra.get("MenuPossibleExtraid"));
                orderDetail.get("OrderDetailExtras").add(extraModel);
            });

            if(menuSearch.Menu.get('MenuPossibleSize').length > 1) {
                let sizeSelected = this.$('input[name=size-' + menuid + ']:checked');

                if(sizeSelected.length === 0)
                {
                    let t = this.i18n();
                    app.error.showAlert(t.error, t.errorSize);
                    return;
                }

                let sizeModel = app.productList.findWhere({MenuTypeid: menuSearch.MenuTypeid})
                                                .get('MenuGroup')
                                                .findWhere({MenuGroupid: menuSearch.MenuGroupid})
                                                .get('Menu')
                                                .findWhere({Menuid: menuid})
                                                .get('MenuPossibleSize')
                                                .findWhere({MenuSizeid: parseInt($(sizeSelected[0]).val())})
                                                .get('MenuSize');

                orderDetail.set('MenuSize', sizeModel);
            } else {
                orderDetail.set('MenuSize', menuSearch.Menu.get('MenuPossibleSize').at(0));
            }

            orderDetail.set('MenuSizeid', orderDetail.get('MenuSize').get('MenuSizeid'));
            orderDetail.set('Menuid', menuid);
            orderDetail.set('Amount', 1);
            orderDetail.set('SinglePrice', menuSearch.Menu.get('Price'));
            orderDetail.set('ExtraDetail', this.$('#extras-text').val());

            this.$('#panel-mixing-text div').each(function(index, mixing){
                let menuid = parseInt($(mixing).attr('data-menuid'));

                let mixingModel = new (orderDetail.get("OrderDetailMixedWiths").model);
                mixingModel.set('Menuid', menuid);
                orderDetail.get("OrderDetailMixedWiths").add(mixingModel);
            });

            this.orderModify.get('OrderDetails').addOnce(orderDetail);

            this.showOverview();
            this.renderOrder();
            this.$('#panel-order').panel("close");
        }

        order_close() {
            this.$('#panel-order').panel("close");
        }

        showOverview() {
            // Fix jQm Tabs handling as its broken by pushstate (normale simple .tabs(active: last) should work...
            this.hideTabs();
            this.$('#overview').show();
            this.$('#tabs-navbar a.ui-btn-active').removeClass('ui-btn-active');
            this.$('#tabs-navbar-overview').addClass('ui-btn-active');
        }

        order_special_add(event) {
            let specialOrderText = this.$('#panel-special-extra').val();
            specialOrderText = $.trim(specialOrderText);

            if(specialOrderText == '') {
                let t = this.i18n();
                app.error.showAlert(t.error, t.errorSpecialOrderText);
                return;
            }

            let orderDetail = new OrderDetail();
            orderDetail.set("OrderDetailExtras", new app.collections.Ordering.OrderDetailExtraCollection());
            orderDetail.set("OrderDetailMixedWiths", new app.collections.Ordering.OrderDetailMixedWithCollection());
            orderDetail.set('Amount', 1);
            orderDetail.set('ExtraDetail', specialOrderText);

            this.orderModify.get('OrderDetails').addOnce(orderDetail);

            this.renderOrder();
            this.$('#panel-special').panel("close");
        }

        order_special_close(event) {
            this.$('#panel-special-extra').val("");
        }

        order_mixing(event) {
            this.isMixing = this.currentMenuid;
            this.$('#panel-order').panel("close");
        }

        menu_item(event) {
            let menuItem = $(event.currentTarget);

            if(this.isMixing !== null) {
                let mixing_menuid = this.isMixing;
                let menuid = parseInt(menuItem.attr('data-menuid'));

                let menuSearch = _.find(app.productList.searchHelper, function(obj) { return obj.Menuid == menuid; });

                let canMix = app.productList.findWhere({MenuTypeid: menuSearch.MenuTypeid})
                                            .get("Allowmixing");

                event.preventDefault();
                event.stopPropagation();

                if(mixing_menuid == menuid) {
                    let t = this.i18n();
                    app.error.showAlert(t.error, t.errorMixWithSelf);
                    return;
                }

                if(canMix == false) {
                    let t = this.i18n();
                    app.error.showAlert(t.error, t.errorMixingNotAllowed);
                    return;
                }

                this.isMixing = null;

                let currentMixing = this.$('#panel-mixing-text');

                if($.trim(currentMixing.html()) == '')
                    currentMixing.html($('<b/>').text('Gemixt mit:'));

                currentMixing.append($('<div/>').attr('data-menuid', menuid).text(menuSearch.Menu.get('Name')));

                this.$('#panel-mixing-text').html(currentMixing.html());

                this.$('#panel-order').panel("open");
            } else {
                this.$('#panel-order').empty();

                let menuid = parseInt(menuItem.attr('data-menuid'));
                this.currentMenuid = menuid;

                let menuSearch = _.find(app.productList.searchHelper, function(obj) {return obj.Menuid == menuid});
                let type = app.productList.findWhere({MenuTypeid: menuSearch.MenuTypeid});

                let templatePanel = _.template(TemplatePanel);
                this.$('#panel-order').html(templatePanel({menu: menuSearch.Menu,
                                                           type: type,
                                                           t: this.i18n(),
                                                           i18n: app.i18n.template}));

                this.$('#panel-order').enhanceWithin()
                                      .trigger("updatelayout")
                                      .panel("open");
            }
        }

        footer_back(event) {
            event.preventDefault();
            window.history.back();
        }

        footer_finish(event) {
            this.$('#verify-dialog').popup('open');
        }

        finished(event) {
            this.orderModify.save()
                            .done(() => {
                                let hasSpecialOrders = false;

                                this.orderModify.get('OrderDetails').each((orderDetail) => {
                                    if(!hasSpecialOrders)
                                        hasSpecialOrders = (orderDetail.get('Menuid') == 0);
                                });

                                if(hasSpecialOrders)
                                    app.ws.api.Trigger("manager-check");

                                app.ws.api.Trigger("distribution-update");

                                this.changeHash("order-invoice/id/" + this.orderModify.get('Orderid'));
                            })
                            .fail(() => {
                                let t = this.i18n();
                                app.error.showAlert(t.error, t.errorOrder);
                            });
        }

        renderOrder() {
            this.$('#selected').empty();

            this.orderItemsView.orderDetails = this.orderModify.get('OrderDetails');
            let detailData = this.orderItemsView.render();
            let totalSumPrice = detailData.totalSumPrice;            
            this.$('#selected').append(this.orderItemsView.$el);   

            if(this.mode == 'edit' && this.oldPrice === undefined) {
                this.oldPrice = parseFloat(totalSumPrice);
                this.$('#total-old').text(app.i18n.toCurrency(this.oldPrice));
            }

            if(this.mode == 'new') {
                this.$('#total').text(app.i18n.toCurrency(totalSumPrice));
            } else {
                this.$('#total-new').text(app.i18n.toCurrency(totalSumPrice));
                this.$('#total-difference').text(app.i18n.toCurrency(totalSumPrice - this.oldPrice));
            }
        }

        render() {
            this.renderTemplate(Template, { mode: this.mode,
                                            order: this.orderModify,
                                            products: app.productList});

            // Broken Tabs widget with Backbone pushstate enabled  - manual fix it
            this.hideTabs();
            this.$("#tabs a[data-role='tab']").click((event) => {
                this.hideTabs();
                this.$($(event.currentTarget).attr('href')).show();
            });

            this.changePage(this);
        }
    }
} );