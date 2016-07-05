/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS"
], function(app, MyPOS){
	"use strict";
	
    var ExtraModel = Backbone.Model.extend({

        defaults: function() {
        	return {
        		menu_extraid: 0,
        		menuid: 0,
        		name: '',
        		price: 0,
        		availability: null
        	};            
        }

    });
    
    return ExtraModel;
});