define([
    "models/db/Ordering/Order"
], function(Order){
    "use strict";
    
    return class OrderCollection extends app.BaseCollection
    {
        getModel() { return Order; }
        url() {return app.API + "DB/Ordering/Order";}
    }
});