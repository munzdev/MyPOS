define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class Invoice extends BaseModel {

        idAttribute() { return 'Invoiceid'; }

        defaults() {
            return {Invoiceid: null,
                    InvoiceTypeid: null,
                    EventContactid: null,
                    CashierUserid: null,
                    EventBankinformationid: null,
                    CustomerEventContactid: null,
                    CanceledInvoiceid: null,
                    Orderid: null,
                    Date: null,
                    Amount: 0,
                    MaturityDate: null,
                    PaymentFinished: null,
                    AmountRecieve: 0};
        }

        parse(response)
        {
            if('InvoiceType' in response)
            {
                response.InvoiceType = new app.models.Invoice.InvoiceType(response.InvoiceType, {parse: true});
            }

            if('User' in response)
            {
                response.User = new app.models.User.User(response.User, {parse: true});
            }

            if('EventContactRelatedByEventContactid' in response)
            {
                response.EventContactRelatedByEventContactid = new app.models.Event.EventContact(response.EventContactRelatedByEventContactid, {parse: true});
            }

            if('EventBankinformation' in response)
            {
                response.EventBankinformation = new app.models.Event.EventBankinformation(response.EventBankinformation, {parse: true});
            }

            if('EventContactRelatedByCustomerEventContactid' in response)
            {
                response.EventContactRelatedByCustomerEventContactid = new app.models.Event.EventContact(response.EventContactRelatedByCustomerEventContactid, {parse: true});
            }

            if('CanceledInvoice' in response)
            {
                response.CanceledInvoice = new app.models.Invoice.Invoice(response.CanceledInvoice, {parse: true});
            }

            if('Order' in response)
            {
                response.Order = new app.models.Order.Order(response.Order, {parse: true});
            }

            if('InvoiceItems' in response)
            {
                if(response.InvoiceItems.toString() == '')
                    response.InvoiceItems = new app.collections.Invoice.InvoiceItemCollection();
                else
                    response.InvoiceItems = new app.collections.Invoice.InvoiceItemCollection(response.InvoiceItems, {parse: true});
            }

            if('PaymentRecieveds' in response)
            {
                if(response.PaymentRecieveds.toString() == '' || JSON.stringify(response.PaymentRecieveds) == '[{"PaymentType":[],"User":[],"PaymentCoupons":[{"Coupon":[]}]}]')
                    response.PaymentRecieveds = new app.collections.Payment.PaymentRecievedCollection();
                else
                    response.PaymentRecieveds = new app.collections.Payment.PaymentRecievedCollection(response.PaymentRecieveds, {parse: true});
            }

            if('InvoiceWarnings' in response)
            {
                if(response.InvoiceWarnings.toString() == '' || JSON.stringify(response.InvoiceWarnings) == '[{"InvoiceWarningType":[]}]')
                    response.InvoiceWarnings = new app.collections.Invoice.InvoiceWarningCollection();
                else
                    response.InvoiceWarnings = new app.collections.Invoice.InvoiceWarningCollection(response.InvoiceWarnings, {parse: true});
            }

            return super.parse(response);
        }

    }
});