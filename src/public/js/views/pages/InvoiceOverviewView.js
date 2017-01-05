define(['collections/custom/invoice/InvoiceOverviewCollection',
        'views/helpers/HeaderView',
        'views/helpers/PaginationView',
        'text!templates/pages/invoice-overview.phtml',
        'text!templates/pages/invoice-overview-item.phtml'
], function(InvoiceOverviewCollection,
            HeaderView,
            PaginationView,
            Template,
            TemplateItem) {
    "use strict";

    return class InvoiceOverviewView extends app.PageView
    {
        initialize(options) {
            _.bindAll(this, "refresh",
                            "renderInvoiceList",
                            "cancel_invoice_popup",
                            "cancel_invoice",
                            "success_popup_close");

            this.search = null;
            this.pagination = new PaginationView(this.refresh);
            this.elementsPerPage = 10;

            if(options)
                this.search = _.extend(this.defaultSearch(), options.search);
            else
                this.search = this.defaultSearch();

            this.invoiceList = new InvoiceOverviewCollection();

            this.refresh();
        }

        events() {
            return {
                'click .cancel-btn': 'cancel_invoice_popup',
                'click .info-btn': 'click_btn_info',
                'click #dialog-continue': 'dialog_continue',
                'click #search-btn': 'click_btn_search',
                'change #invoiceid-search': 'change_invoiceid_search',
                'popupafterclose #cancel-success-popup': 'success_popup_close'
            };
        }

        defaultSearch() {
            return {status: 'unpaid',
                    invoiceid: null,
                    customerid: null,
                    canceled: null,
                    typeid: null,
                    from: null,
                    to: null,
                    userid: app.auth.authUser.get('Userid')};
        }

        refresh() {
            this.$('#invoice-list').empty();
            $.mobile.loading("show");

            this.invoiceList.fetch({data: {search: this.search,
                                           page: this.pagination.currentPage,
                                           elementsPerPage: this.elementsPerPage}})
                            .done(this.renderInvoiceList);
        }

        change_invoiceid_search(event) {
            let invoiceid = $(event.currentTarget).val();
            this.search.invoiceid = invoiceid;
            this.refresh();
        }

        cancel_invoice_popup(event) {
            this.cancelInvoiceCid = $(event.currentTarget).attr('data-invoice-cid');

            let i18n = this.i18n();

            this.$('#dialog-title').text(i18n.cancelInvoice + '?');
            this.$('#dialog-text').text(i18n.cancelInvoiceText + '?');
            this.$('#dialog').popup('open');
        }

        dialog_continue() {
            this.$('#dialog').popup('close')
            this.cancel_invoice();
        }

        cancel_invoice() {
            var invoice = this.invoiceList.get({cid: this.cancelInvoiceCid});
            invoice.save({Cancellation: 1}, {patch: true})
                   .done(this.reload);
        }

        click_btn_info(event) {
            var invoice = this.invoiceList.get({cid: $(event.currentTarget).attr('data-invoice-cid')});

            this.changeHash("invoice/id/" + invoice.get('Invoiceid'));
        }

        click_btn_search() {
            var searchString = '/status/' + this.search.status;

            if(this.search.invoiceid)
                searchString += '/invoiceid/' + this.search.invoiceid;

            if(this.search.customerid)
                searchString += '/customerid/' + this.search.customerid;

            if(this.search.canceled)
                searchString += '/canceled/' + this.search.canceled;

            if(this.search.typeid)
                searchString += '/typeid/' + this.search.typeid;

            if(this.search.from)
                searchString += '/from/' + this.search.from;

            if(this.search.to)
                searchString += '/to/' + this.search.to;

            if(this.search.userid)
                searchString += '/userid/' + this.search.userid;

            this.changeHash('invoice/search' + searchString);
        }

        success_popup_close() {
            this.reload();
        }

        renderInvoiceList() {
            if(!this.rendered) {
                this.render();
                this.rendered = true;
            }

            $.mobile.loading("hide");
            let template = _.template(TemplateItem);
            let i18n = this.i18n();
            let userRoles = app.auth.authUser.get('EventUser').get('UserRoles');

            this.invoiceList.each((invoice) => {
                this.$('#invoice-list').append(template({invoice: invoice,
                                                         userRoles: userRoles,
                                                         t: i18n,
                                                         i18n: app.i18n.template}));
            });
        }

        // Renders all of the Category models on the UI
        render() {
            this.pagination.setTotalPages(Math.ceil(this.invoiceList.count / this.elementsPerPage));
            this.registerSubview(".nav-pagination", this.pagination);

            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template, {invoiceid: this.search.invoiceid});

            this.changePage(this);

            return this;
        }
    }

} );