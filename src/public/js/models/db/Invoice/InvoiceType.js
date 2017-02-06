define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class InvoiceType extends BaseModel {

        idAttribute() { return 'InvoiceTypeid'; }

        defaults() {
            return {InvoiceType: null,
                    Name: ''};
        }
    }
});