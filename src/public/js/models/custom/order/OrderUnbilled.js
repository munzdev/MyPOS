define(["collections/db/Ordering/OrderDetailCollection",
        "collections/db/Payment/CouponCollection"        
], function(OrderDetailCollection,
            CouponCollection){
    "use strict";

    return class OrderUnbilled extends app.BaseModel {
        
        defaults() {
            return {Orderid: null,
                    All: false,
                    UnbilledOrderDetails: new OrderDetailCollection(),
                    UsedCoupons: new CouponCollection()};
        }
        
        url() {return app.API + "Order/Unbilled/" + this.get('Orderid') + '/' + this.get('All');}

        parse(response)
        {
            if('UnbilledOrderDetails' in response)
            {
                response.UnbilledOrderDetails = new OrderDetailCollection(response.UnbilledOrderDetails, {parse: true});
            }

            if('UsedCoupons' in response)
            {
                response.UsedCoupons = new CouponCollection(response.UsedCoupons, {parse: true});
            }
            
            return super.parse(response);
        }
    }
});