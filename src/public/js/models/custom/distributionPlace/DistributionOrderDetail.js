define(["models/BaseModel"
], function(BaseModel) {
    "use strict";

    return class DistributionOrderDetail extends BaseModel {
        urlRoot() { return app.API + "DistributionPlace"; }
        defaults() {
            return {Order: new app.models.Ordering.Order(),
                    OrdersInTodo: new app.collections.Ordering.OrderCollection(),
                    OrderDetailWithSpecialExtra: new app.collections.Ordering.OrderDetailCollection(),
                    MenuExtras: new app.collections.Menu.MenuExtraCollection(),
                    OpenOrders: 0,
                    DoneOrders: 0,
                    NewOrders: 0,
                    Minutes: 0};
        }

        parse(response)
        {
            if('Order' in response && response.Order)
            {
                response.Order = new app.models.Ordering.Order(response.Order, {parse: true});
            }

            if('OrdersInTodo' in response && response.OrdersInTodo)
            {
                response.OrdersInTodo = new app.collections.Ordering.OrderCollection(response.OrdersInTodo, {parse: true});
            }

            if('OrderDetailWithSpecialExtra' in response && response.OrderDetailWithSpecialExtra)
            {
                response.OrderDetailWithSpecialExtra = new app.collections.Ordering.OrderDetailCollection(response.OrderDetailWithSpecialExtra, {parse: true});
            }

            if('MenuExtras' in response && response.MenuExtras)
            {
                response.MenuExtras = new app.collections.Menu.MenuExtraCollection(response.MenuExtras, {parse: true});
            }

            return super.parse(response);
        }
    }
});