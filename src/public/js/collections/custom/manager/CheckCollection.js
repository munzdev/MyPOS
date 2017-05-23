define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class CheckCollection extends BaseCollection
    {
        initialize() {
            this.verified = 0;
        }
        getModel() { return app.models.Ordering.OrderDetail; }
        url() {return app.API + "Manager/Check/Verified/" + this.verified; }
    }
});