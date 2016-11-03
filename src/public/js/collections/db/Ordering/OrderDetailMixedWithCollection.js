define([
    "models/db/Ordering/OrderDetailMixedWith"
], function(OrderDetailMixedWith){
    "use strict";
    
    return class OrderDetailMixedWithCollection extends Backbone.Collection
    {
        model() { return OrderDetailMixedWith; }
        url() {return app.API + "DB/Ordering/OrderDetailMixedWith";}
    }
});