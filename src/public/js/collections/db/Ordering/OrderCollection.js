define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class OrderCollection extends BaseCollection
    {
        getModel() { return app.models.Ordering.Order; }
        url() {return app.API + "DB/Ordering/Order";}
    }
});