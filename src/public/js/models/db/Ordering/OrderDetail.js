define([
    "models/db/Ordering/Order",
    "models/db/Menu/Menu",
    "models/db/Menu/MenuSize",
    "models/db/Menu/MenuGroup",
    "models/db/Menu/Availability",
    "models/db/User/User",
    "collections/db/Ordering/OrderDetailExtraCollection",
    "collections/db/Ordering/OrderDetailMixedWithCollection",
    "collections/db/OIP/OrderInProgressRecievedCollection",
    "collections/db/Invoice/InvoiceItemCollection"
], function(Order,
            Menu,
            MenuSize,
            MenuGroup,
            Availability,
            User,
            OrderDetailExtraCollection,
            OrderDetailMixedWithCollection,
            OrderInProgressRecievedCollection,
            InvoiceItemCollection){
    "use strict";

    return class OrderDetail extends app.BaseModel {
        
        idAttribute() { return 'OrderDetailid'; }

        defaults() {
            return {OrderDetailid: null,
                    Orderid: null,
                    Menuid: null,
                    MenuSizeid: null,
                    MenuGroupid: null,
                    Amount: 0,
                    SinglePrice: 0,
                    SinglePriceModifiedByUserid: null,
                    ExtraDetail: '',
                    Availabilityid: null,
                    AvailabilityAmount: 0,
                    Verified: false,
                    DistributionFinished: null,
                    InvoiceFinished: null};
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
            
            if('InvoiceItems' in response)
            {
                if(response.InvoiceItems.toString() == '')
                    response.InvoiceItems = new InvoiceItemCollection();
                else
                    response.InvoiceItems = new InvoiceItemCollection(response.InvoiceItems, {parse: true});
            }
            
            if('OrderDetailExtras' in response)
            {
                if(response.OrderDetailExtras.toString() == '')
                    response.OrderDetailExtras = new OrderDetailExtraCollection();
                else
                    response.OrderDetailExtras = new OrderDetailExtraCollection(response.OrderDetailExtras, {parse: true});
            }
            
            if('OrderDetailMixedWiths' in response )
            {
                if(response.OrderDetailMixedWiths.toString() == '')
                    response.OrderDetailMixedWiths = new OrderDetailMixedWithCollection();
                else
                    response.OrderDetailMixedWiths = new OrderDetailMixedWithCollection(response.OrderDetailMixedWiths, {parse: true});
            }
            
            if('OrderInProgressRecieveds' in response )
            {
                if(response.OrderInProgressRecieveds.toString() == '')
                    response.OrderInProgressRecieveds = new OrderInProgressRecievedCollection();
                else
                    response.OrderInProgressRecieveds = new OrderInProgressRecievedCollection(response.OrderInProgressRecieveds, {parse: true});
            }
                                    
            return super.parse(response);
        }

    }
});