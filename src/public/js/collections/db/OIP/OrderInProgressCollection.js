define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class OrderInProgressCollection extends BaseCollection
    {
        getModel() { return app.models.OIP.OrderInProgress; }
        url() {return app.API + "DB/OIP/OrderInProgress";}
    }
});