define(["models/db/User/User",
        "models/db/Event/EventContact",
        "models/db/Invoice/InvoiceType",
        "models/db/Event/EventBankinformation",
        "collections/db/Invoice/InvoiceCollection"
], function(User,
            EventContact,
            InvoiceType,
            EventBankinformation,
            InvoiceCollection) {
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

            if('CashierUser' in response)
            {
                response.CashierUser = new User(response.CashierUser, {parse: true});
            }

            if('EventContact' in response)
            {
                response.EventContact = new EventContact(response.EventContact, {parse: true});
            }

            if('EventBankinformation' in response)
            {
                response.EventBankinformation = new EventBankinformation(response.EventBankinformation, {parse: true});
            }

            if('CustomerEventContact' in response)
            {
                response.CustomerEventContact = new EventContact(response.CustomerEventContact, {parse: true});
            }

            if('CanceledInvoice' in response)
            {
                response.CanceledInvoice = new Invoice(response.CanceledInvoice, {parse: true});
            }

            if('InvoiceItems' in response)
            {
                if(response.InvoiceItems.toString() == '')
                    response.InvoiceItems = new InvoiceCollection();
                else
                    response.InvoiceItems = new InvoiceCollection(response.InvoiceItems, {parse: true});
            }

            return super.parse(response);
        }

    }
});