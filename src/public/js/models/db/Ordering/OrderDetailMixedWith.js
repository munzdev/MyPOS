define([
    "models/db/Ordering/OrderDetail",
    "models/db/Menu/Menu",
    
], function(OrderDetail,
            Menu){
    "use strict";

    return class OrderDetailMixedWith extends app.BaseModel {

        defaults() {
            return {OrderDetailid: null,
                    Menuid: null};
        }

        parse(response)
        {
            if('OrderDetail' in response)
            {
                response.OrderDetail = new OrderDetail(response.OrderDetail, {parse: true});
            }
            
            if('Menu' in response)
            {
                response.Menu = new Menu(response.Menu, {parse: true});
            }
            
            return super.parse(response);
        }
    }
});