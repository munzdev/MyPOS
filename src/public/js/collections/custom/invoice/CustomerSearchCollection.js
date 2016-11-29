define(["collections/db/Invoice/CustomerCollection"
], function(CustomerCollection ){
    "use strict";
    
    return class CustomerSearchCollection extends CustomerCollection
    {                
        initialize() {            
            this.name = '';
        }
        url() {return app.API + "Invoice/Customer/" + this.name;}        
    }
});