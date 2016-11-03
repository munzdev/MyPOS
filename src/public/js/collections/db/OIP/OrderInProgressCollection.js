define([
    "models/db/OIP/OrderInProgress"
], function(OrderInProgress){
    "use strict";
    
    return class OrderInProgressCollection extends Backbone.Collection
    {
        model() { return OrderInProgress; }
        url() {return app.API + "DB/OIP/OrderInProgress";}
    }
});