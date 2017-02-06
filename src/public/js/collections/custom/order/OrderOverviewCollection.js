define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class OrderOverviewCollection extends BaseCollection
    {
        getModel() { return app.models.Ordering.Order; }
        url() {return app.API + "Order";}
        parse(response) {
            this.count = response.Count;
            response = response.Order;
            return super.parse(response);
        }
    }
});