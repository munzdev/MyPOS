define([
    "models/db/Ordering/OrderDetail",
    "models/db/Menu/MenuPossibleExtra",
    "app"
], function(OrderDetail,
            MenuPossibleExtra){
    "use strict";

    return class OrderDetailExtra extends Backbone.Model {

        defaults() {
            return {OrderDetailid: 0,
                    MenuPossibleExtraid: 0};
        }

        parse(response)
        {
            if('OrderDetail' in response)
            {
                response.OrderDetail = new OrderDetail(response.OrderDetail, {parse: true});
            }
            
            if('MenuPossibleExtra' in response)
            {
                response.MenuPossibleExtra = new MenuPossibleExtra(response.MenuPossibleExtra, {parse: true});
            }
            
            return super.parse(response);
        }
    }
});