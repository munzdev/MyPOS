/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS",
    "models/order/MixingModel"
], function(app, MyPOS, MixingModel){
	"use strict";
	
    var MixingCollection = Backbone.Collection.extend({
        model: MixingModel
    });
    
    return MixingCollection;
});