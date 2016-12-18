define(["models/db/Invoice/Invoice",
        "models/db/Invoice/InvoiceWarningType"
], function(Invoice,
            InvoiceWarningType){
    "use strict";

    return class InvoiceWarning extends app.BaseModel {

        idAttribute() { return 'InvoiceWarningid'; }

        defaults() {
            return {InvoiceWarningid: null,
                    Invoiceid: null,
                    InvoiceWarningTypeid: null,
                    WarningDate: null,
                    MaturityDate: null,
                    WarningValue: 0};
        }

        parse(response)
        {
            if('Invoice' in response)
            {
                response.Invoice = new Invoice(response.Invoice, {parse: true});
            }

            if('InvoiceWarningType' in response)
            {
                response.InvoiceWarningType = new InvoiceWarningType(response.InvoiceWarningType, {parse: true});
            }

            return super.parse(response);
        }
    }
});