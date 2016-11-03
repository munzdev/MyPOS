define(function(){
    "use strict";

    var OrderDoneInformationModel = Backbone.Model.extend({
        defaults: {
            open_orders: 0,
            done_orders: 0,
            new_orders: 0
        }
    });

    return OrderDoneInformationModel;
});