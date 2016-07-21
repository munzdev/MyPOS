/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "models/order/payments/OrderModel"
], function(OrderModel){
    "use strict";

    var OrderCollection = Backbone.Collection.extend({

        model: OrderModel
    });

    return OrderCollection;
});