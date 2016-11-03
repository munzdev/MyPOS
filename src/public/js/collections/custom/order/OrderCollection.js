define([
    "models/order/MenuModel"
], function(MenuModel){
    "use strict";

    var OrderCollection = app.BaseCollection.extend({
        model: MenuModel,
        addOnce: function(newOrder)
        {
            var sameOrder = this.find( function(order){
                //-- only difference is the amount and backendID if equal. Fix this
                var clone = order.clone();
                clone.set('amount', newOrder.get('amount'));
                clone.set('backendID', newOrder.get('backendID'));

                return JSON.stringify(clone.toJSON()) == JSON.stringify(newOrder.toJSON());
            });

            if(sameOrder)
                sameOrder.set('amount', sameOrder.get('amount') + 1);
            else
                this.add(newOrder);
        }
    });

    return OrderCollection;
});