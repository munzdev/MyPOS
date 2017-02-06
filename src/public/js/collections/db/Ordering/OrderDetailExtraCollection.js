define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class OrderDetailExtraCollection extends BaseCollection
    {
        getModel() { return app.models.Ordering.OrderDetailExtra; }
        url() {return app.API + "DB/Ordering/OrderDetailExtra";}
    }
});