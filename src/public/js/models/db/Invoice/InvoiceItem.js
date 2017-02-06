define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class InvoiceItem extends BaseModel {

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
                response.Invoice = new app.models.Invoice.Invoice(response.Invoice, {parse: true});
            }

            if('OrderDetail' in response)
            {
                response.OrderDetail = new app.models.Ordering.OrderDetail(response.OrderDetail, {parse: true});
            }

            return super.parse(response);
        }
    }
});