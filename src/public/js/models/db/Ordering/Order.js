define([
    "app"
], function(app){
    "use strict";

    return class Order extends Backbone.Model {
        
        idAttribute() { return 'Orderid'; }

        defaults() {
            return {Orderid: 0,
                    EventTableid: 0,
                    Userid: 0,
                    Ordertime: null,
                    Priority: 0,
                    Finished: null};
        }

    }
});