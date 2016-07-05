/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS",
    "models/order/MenuModel"
], function(app, MyPOS, MenuModel){
	"use strict";

    var OrderCollection = Backbone.Collection.extend({
        model: MenuModel,
        addOnce: function(newOrder)
        {
            var sameOrder = this.find( function(order){
                return JSON.stringify(order.toJSON()) == JSON.stringify(newOrder.toJSON());
            });

            if(sameOrder)
                sameOrder.set('amount', sameOrder.get('amount') + 1);
            else
                this.add(newOrder);
        }
    });

    return OrderCollection;
});