define([
    
], function(){
    "use strict";

    return class PaymentType extends Backbone.Model {
        
        idAttribute() { return 'PaymentTypeid'; }

        defaults() {
            return {PaymentTypeid: 0,
                    Name: ''};
        }

    }
});