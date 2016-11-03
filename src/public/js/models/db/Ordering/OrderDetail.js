define([
    "models/db/Ordering/Order",
    "models/db/menu/Menu",
    "models/db/menu/MenuSize",
    "models/db/menu/MenuGroup",
    "models/db/menu/Availability",
    "models/db/User/User",
    
], function(Order,
            Menu,
            MenuSize,
            MenuGroup,
            Availability,
            User){
    "use strict";

    return class OrderDetail extends Backbone.Model {
        
        idAttribute() { return 'OrderDetailid'; }

        defaults() {
            return {OrderDetailid: 0,
                    Orderid: 0,
                    Menuid: 0,
                    MenuSizeid: 0,
                    MenuGroupid: 0,
                    Amount: 0,
                    SinglePrice: 0,
                    SinglePriceModifiedByUserid: 0,
                    ExtraDetail: '',
                    Finished: null,
                    Availabilityid: 0,
                    AvailabilityAmount: 0,
                    Verified: false};
        }
        
        parse(response)
        {
            if('Order' in response)
            {
                response.Order = new Order(response.Order, {parse: true});
            }
            
            if('Menu' in response)
            {
                response.Menu = new Menu(response.Menu, {parse: true});
            }
                       
            if('MenuSize' in response)
            {
                response.MenuSize = new MenuSize(response.MenuSize, {parse: true});
            }
            
            if('MenuGroup' in response)
            {
                response.MenuGroup = new MenuGroup(response.MenuGroup, {parse: true});
            }
            
            if('SinglePriceModifiedByUser' in response)
            {
                response.SinglePriceModifiedByUser = new User(response.SinglePriceModifiedByUser, {parse: true});
            }
            
            if('Availability' in response)
            {
                response.Availability = new Availability(response.Availability, {parse: true});
            }
                                    
            return super.parse(response);
        }

    }
});