define([
    "models/db/Ordering/OrderDetailExtra"
], function(OrderDetailExtra){
    "use strict";
    
    return class OrderDetailExtraCollection extends Backbone.Collection
    {
        model() { return OrderDetailExtra; }
        url() {return app.API + "DB/Ordering/OrderDetailExtra";}
    }
});