define([
    "models/db/Invoice/Customer"
], function(Customer){
    "use strict";
    
    return class CustomerCollection extends app.BaseCollection
    {
        getModel() { return Customer; }
        url() {return app.API + "DB/Invoice/Customer"}
    }
});