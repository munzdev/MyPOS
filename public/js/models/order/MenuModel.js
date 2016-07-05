/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS",
    "models/products/MenuModel",
    "collections/order/MixingCollection"
], function(app,
            MyPOS,
            ProductsMenuModel,
            MixingCollection){
    "use strict";

    var MenuModel = ProductsMenuModel.extend({

        defaults: function() {
            return _.extend({}, ProductsMenuModel.prototype.defaults(), {
                amount: 0,
                open: 0,
                extra: '',
                mixing: new MixingCollection
            })
        }

    });

    return MenuModel;
});