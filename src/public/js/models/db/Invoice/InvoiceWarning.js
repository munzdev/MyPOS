define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class InvoiceWarning extends BaseModel {

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
                response.Invoice = new app.models.Invoice.Invoice(response.Invoice, {parse: true});
            }

            if('InvoiceWarningType' in response)
            {
                response.InvoiceWarningType = new app.models.Invoice.InvoiceWarningType(response.InvoiceWarningType, {parse: true});
            }

            return super.parse(response);
        }
    }
});