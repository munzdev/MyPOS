define([
    "models/db/OIP/OrderInProgressRecieved"
], function(OrderInProgressRecieved){
    "use strict";
    
    return class OrderInProgressRecievedCollection extends Backbone.Collection
    {
        model() { return OrderInProgressRecieved; }
        url() {return app.API + "DB/OIP/OrderInProgressRecieved";}
    }
});