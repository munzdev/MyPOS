define([
    "models/db/Ordering/Order",
    "models/db/Menu/Menu",
    "models/db/Menu/MenuSize",
    "models/db/Menu/MenuGroup",
    "models/db/Menu/Availability",
    "models/db/User/User",
    "collections/db/Ordering/OrderDetailExtraCollection",
    "collections/db/Ordering/OrderDetailMixedWithCollection"
], function(Order,
            Menu,
            MenuSize,
            MenuGroup,
            Availability,
            User,
            OrderDetailExtraCollection,
            OrderDetailMixedWithCollection){
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
            
            if('OrderDetailExtra' in response)
            {
                response.OrderDetailExtra = new OrderDetailExtraCollection(response.OrderDetailExtra, {parse: true});
            }
            
            if('OrderDetailMixedWith' in response)
            {
                response.OrderDetailMixedWith = new OrderDetailMixedWithCollection(response.OrderDetailMixedWith, {parse: true});
            }
                                    
            return super.parse(response);
        }

    }
});