define([
    "app",
    "models/db/Ordering/OrderDetailExtra"
], function(app, OrderDetailExtra){
    "use strict";
    
    return class OrderDetailExtraCollection extends Backbone.Collection
    {
        model() { return OrderDetailExtra; }
        url() {return app.API + "DB/Ordering/OrderDetailExtra";}
    }
});