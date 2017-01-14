define(['models/custom/invoice/InvoiceModel',
        'collections/db/Invoice/InvoiceItemCollection',
        'collections/db/Invoice/InvoiceTypeCollection',
        'views/helpers/HeaderView',
        'views/helpers/CustomerSelectView',
        'text!templates/pages/invoice-add.phtml'
], function(InvoiceModel,
            InvoiceItemCollection,
            InvoiceTypeCollection,
            HeaderView,
            CustomerSelectView,
            Template) {
    "use strict";

    return class InvoiceAddView extends app.PageView
    {
        initialize() {
            this.invoice = new InvoiceModel();
            this.invoice.set('InvoiceItems', new InvoiceItemCollection());

            this.customerSelectView = new CustomerSelectView({selectCallback: this.click_btn_select_customer});
            this.invoiceTypeCollection = new InvoiceTypeCollection();

            $.mobile.loading("show");
            this.invoiceTypeCollection.fetch()
                                      .done(() => {
                                            $.mobile.loading("hide");
                                            this.render();
                                      });
        }

        events() {
            return {'click #use-customer': 'click_btn_use_customer'}
        }

        click_btn_use_customer() {
            this.customerSelectView.show();
        }

        click_btn_select_customer(customer) {

        }

        // Renders all of the Category models on the UI
        render() {
            let header = new HeaderView();
            this.registerSubview(".nav-header", header);
            this.registerAppendview(this.customerSelectView);

            this.renderTemplate(Template, {invoiceTypeList: this.invoiceTypeCollection});

            this.changePage(this);

            return this;
        }
    }
} );