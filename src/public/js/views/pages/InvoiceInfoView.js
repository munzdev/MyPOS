define(['models/custom/invoice/InvoiceInfo',
        'models/custom/invoice/InvoiceModel',
        'views/helpers/HeaderView',
        'text!templates/pages/invoice-info.phtml'
], function(InvoiceInfo,
            InvoiceModel,
            HeaderView,
            Template) {
    "use strict";

    return class InvoiceInfoView extends app.PageView
    {
        initialize(options) {

            this.invoiceid = options.invoiceid;

            this.invoiceInfo = new InvoiceInfo();
            this.invoiceInfo.set('Invoiceid', options.invoiceid);

            $.mobile.loading("show");
            this.invoiceInfo.fetch()
                            .done(() => {
                                $.mobile.loading("hide");
                                this.render();
                            });
        }

        events() {
            return {'click #back': 'click_btn_back',
                    'click #cancel-btn': 'click_btn_cancel',
                    'click #add-payment-btn': 'click_btn_add_payment',
                    'click #dialog-continue': 'cancel_dialog_continue'}
        }

        click_btn_back(event) {
            event.preventDefault();
            window.history.back();
        }

        click_btn_cancel() {
            this.$('#dialog').popup('open');
        }

        cancel_dialog_continue() {
            this.$('#dialog').popup('close');

            let invoice = new InvoiceModel();
            invoice.set('Invoiceid', this.invoiceInfo.get('Invoiceid'));
            invoice.save({Cancellation: 1}, {patch: true})
                   .done(this.reload);
        }

        click_btn_add_payment() {
            this.changeHash("invoice/id/" + this.invoiceInfo.get('Invoiceid') + '/payment');
        }

        // Renders all of the Category models on the UI
        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template, {invoiceInfo: this.invoiceInfo});

            this.changePage(this);

            return this;
        }
    }
} );