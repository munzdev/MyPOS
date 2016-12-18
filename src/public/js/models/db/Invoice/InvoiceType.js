define([], function() {
    "use strict";

    return class InvoiceType extends app.BaseModel {

        idAttribute() { return 'InvoiceTypeid'; }

        defaults() {
            return {InvoiceType: null,
                    Name: ''};
        }
    }
});