define(["models/db/Event/EventContact"
], function(EventContact) {
    "use strict";

    return class Customer extends EventContact {
        urlRoot() { return app.API + "Invoice/Customer"; }
    }
});