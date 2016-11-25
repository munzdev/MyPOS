define([
    "models/db/Ordering/OrderDetail",
    "models/db/Menu/MenuPossibleExtra",
    
], function(OrderDetail,
            MenuPossibleExtra){
    "use strict";

    return class OrderDetailExtra extends app.BaseModel {

        defaults() {
            return {OrderDetailid: null,
                    MenuPossibleExtraid: null};
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