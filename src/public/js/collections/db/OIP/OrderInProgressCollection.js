define([
    "models/db/OIP/OrderInProgress"
], function(OrderInProgress){
    "use strict";
    
    return class OrderInProgressCollection extends app.BaseCollection
    {
        getModel() { return OrderInProgress; }
        url() {return app.API + "DB/OIP/OrderInProgress";}
    }
});