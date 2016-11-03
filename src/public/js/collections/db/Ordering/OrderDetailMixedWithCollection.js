define([
    "models/db/Ordering/OrderDetailMixedWith"
], function(OrderDetailMixedWith){
    "use strict";
    
    return class OrderDetailMixedWithCollection extends app.BaseCollection
    {
        getModel() { return OrderDetailMixedWith; }
        url() {return app.API + "DB/Ordering/OrderDetailMixedWith";}
    }
});