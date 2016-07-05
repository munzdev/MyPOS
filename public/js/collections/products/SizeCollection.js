/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS",
    "models/products/SizeModel"
], function(app, MyPOS, SizeModel){
	"use strict";
	
    var SizeCollection = Backbone.Collection.extend({

        model: SizeModel

    });
    
    return SizeCollection;
});