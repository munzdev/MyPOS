define(['views/helpers/HeaderView',
        'text!templates/pages/invoice-payment.phtml'
], function(HeaderView,
            Template) {
    "use strict";

    return class InvoicePaymentView extends app.PageView
    {
        initialize() {
            this.render();
        }

        events() {
            return {}
        }

        // Renders all of the Category models on the UI
        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template);

            this.changePage(this);

            return this;
        }
    }
} );