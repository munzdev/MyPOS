define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class OrderInProgressRecievedCollection extends BaseCollection
    {
        getModel() { return app.models.OIP.OrderInProgressRecieved; }
        url() {return app.API + "DB/OIP/OrderInProgressRecieved";}
    }
});