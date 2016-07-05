/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS"
], function(app, MyPOS){
	"use strict";

    var SizeModel = Backbone.Model.extend({

        defaults: function() {
        	return {
        		menu_sizeid: 0,
        		menuid: 0,
        		name: '',
                        factor: 0,
        		price: 0
        	};
        }

    });

    return SizeModel;
});