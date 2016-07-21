/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS"
], function(app, MyPOS){
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
                finished: false
            };
        }
    });

    return OrdersOverviewModel;
});