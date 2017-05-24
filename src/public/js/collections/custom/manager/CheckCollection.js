define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class CheckCollection extends BaseCollection
    {
        getModel() { return app.models.Ordering.OrderDetail; }
        url() { return app.API + "Manager/Check" }
    }
});