/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS",
    "models/products/MenuModel"
], function(app, MyPOS, MenuModel){
	"use strict";
	
    var MenuCollection = Backbone.Collection.extend({
        model: MenuModel
    });
    
    return MenuCollection;
});