define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class OrderDetail extends BaseModel {

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
                response.Order = new app.models.Ordering.Order(response.Order, {parse: true});
            }

            if('Menu' in response)
            {
                response.Menu = new app.models.Menu.Menu(response.Menu, {parse: true});
            }

            if('MenuSize' in response)
            {
                response.MenuSize = new app.models.Menu.MenuSize(response.MenuSize, {parse: true});
            }

            if('MenuGroup' in response)
            {
                response.MenuGroup = new app.models.Menu.MenuGroup(response.MenuGroup, {parse: true});
            }

            if('SinglePriceModifiedByUser' in response)
            {
                response.SinglePriceModifiedByUser = new app.models.User.User(response.SinglePriceModifiedByUser, {parse: true});
            }

            if('Availability' in response)
            {
                response.Availability = new app.models.Menu.Availability(response.Availability, {parse: true});
            }

            if('InvoiceItems' in response)
            {
                if(response.InvoiceItems.toString() == '')
                    response.InvoiceItems = new app.collections.Invoice.InvoiceItemCollection();
                else
                    response.InvoiceItems = new app.collections.Invoice.InvoiceItemCollection(response.InvoiceItems, {parse: true});
            }

            if('OrderDetailExtras' in response)
            {
                if(response.OrderDetailExtras.toString() == '' || JSON.stringify(response.OrderDetailExtras) == '[{"MenuPossibleExtra":{"MenuExtra":[]}}]')
                    response.OrderDetailExtras = new app.collections.Ordering.OrderDetailExtraCollection();
                else
                    response.OrderDetailExtras = new app.collections.Ordering.OrderDetailExtraCollection(response.OrderDetailExtras, {parse: true});
            }

            if('OrderDetailMixedWiths' in response )
            {
                if(response.OrderDetailMixedWiths.toString() == '')
                    response.OrderDetailMixedWiths = new app.collections.Ordering.OrderDetailMixedWithCollection();
                else
                    response.OrderDetailMixedWiths = new app.collections.Ordering.OrderDetailMixedWithCollection(response.OrderDetailMixedWiths, {parse: true});
            }

            if('OrderInProgressRecieveds' in response )
            {
                if(response.OrderInProgressRecieveds.toString() == '')
                    response.OrderInProgressRecieveds = new app.collections.OIP.OrderInProgressRecievedCollection();
                else
                    response.OrderInProgressRecieveds = new app.collections.OIP.OrderInProgressRecievedCollection(response.OrderInProgressRecieveds, {parse: true});
            }

            return super.parse(response);
        }

    }
});