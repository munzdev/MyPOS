define([
    "models/db/Ordering/Order",
    "models/db/User/User",
    "models/db/Menu/MenuGroup",
    "app"
], function(Order,
            User,
            MenuGroup){
    "use strict";

    return class OrderInProgress extends Backbone.Model {
        
        idAttribute() { return 'OrderInProgressid'; }

        defaults() {
            return {OrderInProgressid: 0,
                    Orderid: 0,
                    Userid: 0,
                    MenuGroupid: 0,
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
            
            return super.response(response);
        }
    }
});