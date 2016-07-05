/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS",
    "collections/products/MenuCollection"
], function(app, MyPOS, MenuCollection){
	"use strict";
	
    var GroupModel = Backbone.Model.extend({

        defaults: function() {
        	return {
        		menu_groupid: 0,
        		menu_typeid: 0,
        		name: '',
                menues: new MenuCollection
        	};            
        },
        
        parse: function(response)
        {
            response.menues = new MenuCollection(response.menues, {parse: true});
            return response;
        }

    });
    
    return GroupModel;
});