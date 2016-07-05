/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "models/products/SizeModel",
], function(ProductsSizeModel){
    "use strict";

    var SizeModel = ProductsSizeModel.extend({

        defaults: function() {
            return _.extend({}, ProductsSizeModel.prototype.defaults(), {

            })
        }

    });

    return SizeModel;
});