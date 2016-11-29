define(["models/db/Invoice/Customer"
], function(CustomerModel) {
    "use strict";

    return class Customer extends CustomerModel {
        urlRoot() { return app.API + "Invoice/Customer"; }
    }
});