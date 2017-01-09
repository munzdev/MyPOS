define(['models/custom/invoice/InvoiceModel',
        'collections/db/Invoice/InvoiceItemCollection',
        'collections/custom/invoice/CustomerSearchCollection',
        'collections/db/Invoice/InvoiceTypeCollection',
        'views/helpers/HeaderView',
        'text!templates/pages/invoice-add.phtml'
], function(InvoiceModel,
            InvoiceItemCollection,
            CustomerSearchCollection,
            InvoiceTypeCollection,
            HeaderView,
            Template) {
    "use strict";

    return class InvoiceAddView extends app.PageView
    {
        initialize() {
            this.invoice = new InvoiceModel();
            this.invoice.set('InvoiceItems', new InvoiceItemCollection());

            this.customerSearch = new CustomerSearchCollection();
            this.invoiceTypeCollection = new InvoiceTypeCollection();

            $.mobile.loading("show");
            this.invoiceTypeCollection.fetch()
                                      .done(() => {
                                            $.mobile.loading("hide");
                                            this.render();
                                      });
        }

        events() {
            return {}
        }

        // Renders all of the Category models on the UI
        render() {
            let header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template, {invoiceTypeList: this.invoiceTypeCollection});

            this.changePage(this);

            return this;
        }
    }
} );