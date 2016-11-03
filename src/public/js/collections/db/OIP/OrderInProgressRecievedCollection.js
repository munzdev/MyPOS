define([
    "models/db/OIP/OrderInProgressRecieved"
], function(OrderInProgressRecieved){
    "use strict";
    
    return class OrderInProgressRecievedCollection extends app.BaseCollection
    {
        getModel() { return OrderInProgressRecieved; }
        url() {return app.API + "DB/OIP/OrderInProgressRecieved";}
    }
});