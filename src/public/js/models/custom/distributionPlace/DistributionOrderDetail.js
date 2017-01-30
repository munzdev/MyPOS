define(['models/db/Ordering/Order',
        'collections/db/Ordering/OrderCollection',
        'collections/db/Ordering/OrderDetailCollection',
        'collections/db/Menu/MenuExtraCollection'
], function(Order,
            OrderCollection,
            OrderDetailCollection,
            MenuExtraCollection){
    "use strict";

    return class DistributionOrderDetail extends app.BaseModel {
        urlRoot() { return app.API + "DistributionPlace"; }
        defaults() {
            return {Order: new Order(),
                    OrdersInTodo: new OrderCollection(),
                    OrderDetailWithSpecialExtra: new OrderDetailCollection(),
                    MenuExtras: new MenuExtraCollection(),
                    OpenOrders: 0,
                    DoneOrders: 0,
                    NewOrders: 0,
                    Minutes: 0};
        }

        parse(response)
        {
            if('Order' in response && response.Order)
            {
                response.Order = new Order(response.Order, {parse: true});
            }

            if('OrdersInTodo' in response && response.OrdersInTodo)
            {
                response.OrdersInTodo = new OrderCollection(response.OrdersInTodo, {parse: true});
            }

            if('OrderDetailWithSpecialExtra' in response && response.OrderDetailWithSpecialExtra)
            {
                response.OrderDetailWithSpecialExtra = new OrderDetailCollection(response.OrderDetailWithSpecialExtra, {parse: true});
            }

            if('MenuExtras' in response && response.MenuExtras)
            {
                response.MenuExtras = new MenuExtraCollection(response.MenuExtras, {parse: true});
            }

            return super.parse(response);
        }
    }
});