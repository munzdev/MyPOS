define([
    "app",
    "models/db/Ordering/Order"
], function(app, Order){
    "use strict";
    
    return class OrderCollection extends Backbone.Collection
    {
        model() { return Order; }
        url() {return app.API + "DB/Ordering/Order";}
    }
});