define([
    "app",
    "models/db/OIP/OrderInProgress"
], function(app, OrderInProgress){
    "use strict";
    
    return class OrderInProgressCollection extends Backbone.Collection
    {
        model() { return OrderInProgress; }
        url() {return app.API + "DB/OIP/OrderInProgress";}
    }
});