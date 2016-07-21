/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS",
    "models/products/ExtraModel"
], function(app, MyPOS, ExtraModel){
	"use strict";
	
    var ExtraCollection = Backbone.Collection.extend({

        model: ExtraModel

    });
    
    return ExtraCollection;
});