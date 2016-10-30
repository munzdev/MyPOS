define([
    "app"
], function(app){
    "use strict";

    return class OrderInProgress extends Backbone.Model {
        
        idAttribute() { return 'OrderInProgressid'; }

        defaults() {
            return {OrderInProgressid: 0,
                    Orderid: 0,
                    Userid: 0,
                    MenuGroupid: 0,
                    Begin: null,
                    Done: null};
        }

    }
});