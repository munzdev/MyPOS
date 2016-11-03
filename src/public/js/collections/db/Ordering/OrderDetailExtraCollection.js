define([
    "models/db/Ordering/OrderDetailExtra"
], function(OrderDetailExtra){
    "use strict";
    
    return class OrderDetailExtraCollection extends app.BaseCollection
    {
        getModel() { return OrderDetailExtra; }
        url() {return app.API + "DB/Ordering/OrderDetailExtra";}
    }
});