define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class PaymentType extends BaseModel {

        idAttribute() { return 'PaymentTypeid'; }

        defaults() {
            return {PaymentTypeid: null,
                    Name: ''};
        }

    }
});