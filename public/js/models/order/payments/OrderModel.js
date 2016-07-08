/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define(function(){
    "use strict";

    var OrderModel = Backbone.Model.extend({

        defaults: {
            orders_detailid: 0,
            menuid: 0,
            amount: 0,
            single_price: 0,
            extra_detail: null,
            verified: false,
            amount_payed: 0
        }

    });

    return OrderModel;
});