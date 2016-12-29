define([ "Webservice",
         'collections/custom/order/OrderOverviewCollection',
         'views/helpers/HeaderView',
         'views/helpers/PaginationView',
         'text!templates/pages/order-overview.phtml',
         'text!templates/pages/order-overview-item.phtml'
], function(Webservice,
            OrderOverviewCollection,
            HeaderView,
            PaginationView,
            Template,
            TemplateItem) {
    "use strict";

    return class OrderOverviewView extends app.PageView
    {
        initialize(options) {
            _.bindAll(this, "render",
                            "refresh",
                            "renderOrdersList",
                            "cancel_order_popup",
                            "cancel_order",
                            "success_popup_close");

            this.search = null;
            this.pagination = new PaginationView(this.refresh);
            this.elementsPerPage = 10;

            if(options)
                this.search = options.search;

            this.ordersList = new OrderOverviewCollection();

            this.refresh();
        }

        events() {
            return {
                'click .cancel-btn': 'cancel_order_popup',
                'click .pay-btn': 'click_btn_pay',
                'click .info-btn': 'click_btn_info',
                'click .modify-btn': 'click_btn_modify',
                'click #dialog-continue': 'dialog_continue',
                'click #search-btn': 'click_btn_search',
                'click .manage-priority-btn': 'click_btn_priority',
                'click .manage-price-btn': 'click_btn_price',
                'popupafterclose #cancel-success-popup': 'success_popup_close'
            };
        }

        refresh() {
            this.$('#order-list').empty();
            $.mobile.loading("show");

            if(this.search)
                this.ordersList.fetch({data: {search: this.search,
                                              page: this.pagination.currentPage,
                                              elementsPerPage: this.elementsPerPage}})
                               .done(this.renderOrdersList);
            else
                this.ordersList.fetch({data: {page: this.pagination.currentPage,
                                              elementsPerPage: this.elementsPerPage}})
                               .done(this.renderOrdersList);
        }

        cancel_order_popup(event) {
            this.cancelOrderCid = $(event.currentTarget).attr('data-order-cid');
            this.dialogMode = 'cancel';

            let i18n = this.i18n();

            $('#dialog-title').text(i18n.cancelOrder + '?');
            $('#dialog-text').text(i18n.cancelOrderText + '?');
            $('#dialog').popup('open');
        }

        dialog_continue() {
            $('#dialog').popup('close')

            if(this.dialogMode == 'cancel')
                this.cancel_order();
            else if(this.dialogMode == 'priority')
                this.set_priority();
        }

        cancel_order() {
            var order = this.ordersList.get({cid: this.cancelOrderCid});

            var webservice = new Webservice();
            webservice.action = "Orders/MakeCancel";
            webservice.formData = {orderid: this.cancelOrderCid};

            webservice.callback = {
                success: function() {
                    $('#cancel-success-popup').popup("open");
                }
            };
            webservice.call();
        }

        click_btn_pay(event) {
            var order = this.ordersList.get({cid: $(event.currentTarget).attr('data-order-cid')});

            this.changeHash("order-invoice/id/" + order.get('Orderid'));
        }

        click_btn_modify(event) {
            var order = this.ordersList.get({cid: $(event.currentTarget).attr('data-order-cid')});

            this.changeHash("order-modify/id/" + order.get('Orderid'));
        }

        click_btn_info(event) {
            var order = this.ordersList.get({cid: $(event.currentTarget).attr('data-order-cid')});

            this.changeHash("order-info/id/" + order.get('Orderid'));
        }

        click_btn_priority(event) {
            this.priorityOrderCid = $(event.currentTarget).attr('data-order-cid');
            this.dialogMode = 'priority';

            let i18n = this.i18n();

            $('#dialog-title').text(i18n.changePriority + "?");
            $('#dialog-text').text(i18n.changePriorityText + "?");
            $('#dialog').popup('open');
        }

        click_btn_price(event) {
            var orderid = $(event.currentTarget).attr('data-order-cid');

            this.changeHash("order-modify-price/orderid/" + orderid);
        }

        set_priority() {
            var order = this.ordersList.get({cid: this.priorityOrderCid});
            order.save({Priority: 1}, {patch: true})
                 .done(this.reload);
        }

        click_btn_search() {
            this.changeHash(Backbone.history.getFragment() + "/search/");
        }

        success_popup_close() {
            this.reload();
        }

        renderOrdersList() {
            if(!this.rendered) {
                this.render();
                this.rendered = true;
            }

            $.mobile.loading("hide");
            let template = _.template(TemplateItem);
            let i18n = this.i18n();
            let userRoles = app.auth.authUser.get('EventUser').get('UserRoles');

            this.ordersList.each((order) => {
                this.$('#order-list').append(template({order: order,
                                                       userRoles: userRoles,
                                                       t: i18n,
                                                       i18n: app.i18n.template}));
            });
        }

        // Renders all of the Category models on the UI
        render() {
            this.pagination.setTotalPages(Math.ceil(this.ordersList.count / this.elementsPerPage));
            this.registerSubview(".nav-pagination", this.pagination);

            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template);

            this.changePage(this);

            return this;
        }
    }

} );