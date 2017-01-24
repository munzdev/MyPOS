define(["models/db/User/User",
        "models/db/Event/EventContact",
        "models/db/Invoice/InvoiceType",
        "models/db/Event/EventBankinformation",
        "collections/db/Invoice/InvoiceItemCollection",
        "collections/db/Payment/PaymentRecievedCollection",
        "collections/db/Invoice/InvoiceWarningCollection"
], function(User,
            EventContact,
            InvoiceType,
            EventBankinformation,
            InvoiceItemCollection,
            PaymentRecievedCollection,
            InvoiceWarningCollection) {
    "use strict";

    return class Invoice extends app.BaseModel {

        idAttribute() { return 'Invoiceid'; }

        defaults() {
            return {Invoiceid: null,
                    InvoiceTypeid: null,
                    EventContactid: null,
                    CashierUserid: null,
                    EventBankinformationid: null,
                    CustomerEventContactid: null,
                    CanceledInvoiceid: null,
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
                response.InvoiceType = new InvoiceType(response.InvoiceType, {parse: true});
            }

            if('User' in response)
            {
                response.User = new User(response.User, {parse: true});
            }

            if('EventContactRelatedByEventContactid' in response)
            {
                response.EventContactRelatedByEventContactid = new EventContact(response.EventContactRelatedByEventContactid, {parse: true});
            }

            if('EventBankinformation' in response)
            {
                response.EventBankinformation = new EventBankinformation(response.EventBankinformation, {parse: true});
            }

            if('EventContactRelatedByCustomerEventContactid' in response)
            {
                response.EventContactRelatedByCustomerEventContactid = new EventContact(response.EventContactRelatedByCustomerEventContactid, {parse: true});
            }

            if('CanceledInvoice' in response)
            {
                response.CanceledInvoice = new Invoice(response.CanceledInvoice, {parse: true});
            }

            if('InvoiceItems' in response)
            {
                if(response.InvoiceItems.toString() == '')
                    response.InvoiceItems = new InvoiceItemCollection();
                else
                    response.InvoiceItems = new InvoiceItemCollection(response.InvoiceItems, {parse: true});
            }

            if('PaymentRecieveds' in response)
            {
                if(response.PaymentRecieveds.toString() == '' || JSON.stringify(response.PaymentRecieveds) == '[{"PaymentType":[],"User":[],"PaymentCoupons":[{"Coupon":[]}]}]')
                    response.PaymentRecieveds = new PaymentRecievedCollection();
                else
                    response.PaymentRecieveds = new PaymentRecievedCollection(response.PaymentRecieveds, {parse: true});
            }

            if('InvoiceWarnings' in response)
            {
                if(response.InvoiceWarnings.toString() == '' || JSON.stringify(response.InvoiceWarnings) == '[{"InvoiceWarningType":[]}]')
                    response.InvoiceWarnings = new InvoiceWarningCollection();
                else
                    response.InvoiceWarnings = new InvoiceWarningCollection(response.InvoiceWarnings, {parse: true});
            }

            return super.parse(response);
        }

    }
});