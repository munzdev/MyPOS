define(["models/BaseModel"
], function(BaseModel) {
    "use strict";

    return class OrderUnbilled extends BaseModel {

        defaults() {
            return {Orderid: null,
                    All: false,
                    Customer: null,
                    UnbilledOrderDetails: new app.collections.Ordering.OrderDetailCollection(),
                    UsedCoupons: new app.collections.Payment.CouponCollection()};
        }

        url() {return app.API + "Order/Unbilled/" + this.get('Orderid') + '/' + this.get('All');}

        parse(response)
        {
            if('UnbilledOrderDetails' in response)
            {
                response.UnbilledOrderDetails = new app.collections.Ordering.OrderDetailCollection(response.UnbilledOrderDetails, {parse: true});
            }

            if('UsedCoupons' in response)
            {
                response.UsedCoupons = new app.collections.Payment.CouponCollection(response.UsedCoupons, {parse: true});
            }

            return super.parse(response);
        }
    }
});