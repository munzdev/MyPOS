define([
    "models/db/Ordering/Order",
    "models/db/User/User",
    "models/db/Menu/MenuGroup",
    
], function(Order,
            User,
            MenuGroup){
    "use strict";

    return class OrderInProgress extends app.BaseModel {
        
        idAttribute() { return 'OrderInProgressid'; }

        defaults() {
            return {OrderInProgressid: null,
                    Orderid: null,
                    Userid: null,
                    MenuGroupid: null,
                    Begin: null,
                    Done: null};
        }

        parse(response)
        {
            if('Order' in response)
            {
                response.Order = new Order(response.Order, {parse: true});
            }
            
            if('User' in response)
            {
                response.User = new User(response.User, {parse: true});
            }
            
            if('MenuGroup' in response)
            {
                response.MenuGroup = new MenuGroup(response.MenuGroup, {parse: true});
            }
            
            return super.parse(response);
        }
    }
});