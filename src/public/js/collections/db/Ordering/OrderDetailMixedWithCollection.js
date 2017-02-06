define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class OrderDetailMixedWithCollection extends BaseCollection
    {
        getModel() { return app.models.Ordering.OrderDetailMixedWith; }
        url() {return app.API + "DB/Ordering/OrderDetailMixedWith";}
    }
});