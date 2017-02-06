define(["collections/db/Event/EventContactCollection"
], function(EventContactCollection){
    "use strict";

    return class CustomerSearchCollection extends EventContactCollection
    {
        initialize() {
            this.name = '';
        }
        url() {return app.API + "Invoice/Customer/" + this.name;}
    }
});