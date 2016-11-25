define([
    
], function(){
    "use strict";

    return class PaymentType extends app.BaseModel {
        
        idAttribute() { return 'PaymentTypeid'; }

        defaults() {
            return {PaymentTypeid: null,
                    Name: ''};
        }

    }
});