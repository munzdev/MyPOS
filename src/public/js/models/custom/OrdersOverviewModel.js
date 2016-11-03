define(function(){
    "use strict";

    var OrdersOverviewModel = Backbone.Model.extend({

        defaults: function() {
            return {
                orderid: 0,
                tableid: 0,
                table_name: '',
                price: 0,
                status: '',
                open: 0,
                button_info: false,
                button_edit: false,
                button_pay: false,
                button_cancel: false,
                manage: false,
                finished: false
            };
        }
    });

    return OrdersOverviewModel;
});