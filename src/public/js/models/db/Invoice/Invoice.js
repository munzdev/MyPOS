define(["models/db/User/User",
        "models/db/Event/EventContact",
        "models/db/Invoice/Customer",
        "collections/db/Invoice/InvoiceCollection"
], function(User,
            EventContact,
            Customer,
            InvoiceCollection) {
    "use strict";

    return class Invoice extends app.BaseModel {

        idAttribute() { return 'Invoiceid'; }

        defaults() {
            return {Invoiceid: null,
                    EventContactid: null,
                    CashierUserid: null,
                    Date: null,
                    Canceled: null};
        }

        parse(response)
        {
            if('CashierUser' in response)
            {
                response.CashierUser = new User(response.CashierUser, {parse: true});
            }

            if('EventContact' in response)
            {
                response.EventContact = new EventContact(response.EventContact, {parse: true});
            }

            if('Customer' in response)
            {
                response.Customer = new Customer(response.Customer, {parse: true});
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