define(['models/custom/invoice/InvoiceInfo',
        'views/helpers/HeaderView',
        'text!templates/pages/invoice-info.phtml'
], function(InvoiceInfo,
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
                    'click #search': 'click_btn_search',
                    'click #select-customer': "click_select_customer",
                    'click #customer-search': "click_customer_search",
                    'popupafterclose #select-customer-popup': 'close_select_customer_popup'}
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