define([
    "models/db/Invoice/Invoice",
    "models/db/Ordering/OrderDetail",
    
], function(Invoice,
            OrderDetail){
    "use strict";

    return class InvoiceItem extends Backbone.Model {
        
        idAttribute() { return 'InvoiceItemid'; }
    
        defaults() {
            return {InvoiceItemid: 0,
                    Invoiceid: 0,
                    OrderDetailid: 0,
                    Amount: 0,
                    Price: 0,
                    Description: '',
                    Tax: 0};
        }

        parse(response)
        {                       
            if('Invoice' in response)
            {
                response.Invoice = new Invoice(response.Invoice, {parse: true});
            }
            
            if('OrderDetail' in response)
            {
                response.OrderDetail = new OrderDetail(response.OrderDetail, {parse: true});
            }
            
            return super.parse(response);
        }
    }
});