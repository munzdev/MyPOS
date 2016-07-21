/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS",
    "models/products/GroupModel"
], function(app, MyPOS, GroupModel){
	"use strict";
	
    var GroupCollection = Backbone.Collection.extend({
        model: GroupModel
    });
    
    return GroupCollection;
});