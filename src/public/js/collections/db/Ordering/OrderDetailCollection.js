define([
    "models/db/Ordering/OrderDetail"
], function(OrderDetail){
    "use strict";
    
    return class OrderDetailCollection extends app.BaseCollection
    {
        getModel() { return OrderDetail; }
        url() {return app.API + "DB/Ordering/OrderDetail";}
        
        
        addOnce(newOrderDetail) {
            var sameOrder = this.find( function(orderDetail){
                //-- only difference is the amount if equal. Fix this
                var clone = orderDetail.clone();
                clone.set('Amount', newOrderDetail.get('Amount'));

                return JSON.stringify(clone.toJSON()) == JSON.stringify(newOrderDetail.toJSON());
            });

            if(sameOrder)
                sameOrder.set('Amount', sameOrder.get('Amount') + 1);
            else
                this.add(newOrderDetail);
        }
    }
});