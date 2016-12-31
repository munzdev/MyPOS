define(['collections/custom/order/OrderOverviewCollection',
        'views/helpers/HeaderView',
        'views/helpers/PaginationView',
        'text!templates/pages/order-overview.phtml',
        'text!templates/pages/order-overview-item.phtml'
], function(OrderOverviewCollection,
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
                this.search = _.extend(this.defaultSearch(), options.search);
            else
                this.search = this.defaultSearch();

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
                'change #tableNr-search': 'change_tableNr_search',
                'popupafterclose #cancel-success-popup': 'success_popup_close'
            };
        }

        defaultSearch() {
            return {status: 'open',
                    orderid: null,
                    tableNr: null,
                    from: null,
                    to: null,
                    userid: app.auth.authUser.get('Userid')};
        }

        refresh() {
            this.$('#order-list').empty();
            $.mobile.loading("show");

            this.ordersList.fetch({data: {search: this.search,
                                          page: this.pagination.currentPage,
                                          elementsPerPage: this.elementsPerPage}})
                           .done(this.renderOrdersList);
        }

        change_tableNr_search(event) {
            let tableNr = $(event.currentTarget).val();
            this.search.tableNr = tableNr;
            this.refresh();
        }

        cancel_order_popup(event) {
            this.cancelOrderCid = $(event.currentTarget).attr('data-order-cid');
            this.dialogMode = 'cancel';

            let i18n = this.i18n();

            this.$('#dialog-title').text(i18n.cancelOrder + '?');
            this.$('#dialog-text').text(i18n.cancelOrderText + '?');
            this.$('#dialog').popup('open');
        }

        dialog_continue() {
            this.$('#dialog').popup('close')

            if(this.dialogMode == 'cancel')
                this.cancel_order();
            else if(this.dialogMode == 'priority')
                this.set_priority();
        }

        cancel_order() {
            var order = this.ordersList.get({cid: this.cancelOrderCid});
            order.save({Cancellation: 1}, {patch: true})
                 .done(this.reload);
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

            this.$('#dialog-title').text(i18n.changePriority + "?");
            this.$('#dialog-text').text(i18n.changePriorityText + "?");
            this.$('#dialog').popup('open');
        }

        click_btn_price(event) {
            var order = this.ordersList.get({cid: $(event.currentTarget).attr('data-order-cid')});

            this.changeHash("order-modify-price/id/" + order.get('Orderid'));
        }

        set_priority() {
            var order = this.ordersList.get({cid: this.priorityOrderCid});
            order.save({Priority: 1}, {patch: true})
                 .done(this.reload);
        }

        click_btn_search() {
            var searchString = '/status/' + this.search.status;

            if(this.search.orderid)
                searchString += '/orderid/' + this.search.orderid;

            if(this.search.tableNr)
                searchString += '/tableNr/' + this.search.tableNr;

            if(this.search.from)
                searchString += '/from/' + this.search.from;

            if(this.search.to)
                searchString += '/to/' + this.search.to;

            if(this.search.userid)
                searchString += '/userid/' + this.search.userid;

            this.changeHash('order-overview/search' + searchString);
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

            this.renderTemplate(Template, {tableNr: this.search.tableNr});

            this.changePage(this);

            return this;
        }
    }

} );