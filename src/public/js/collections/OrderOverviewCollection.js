/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS",
    "models/OrdersOverviewModel"
], function(app, MyPOS, OrdersOverviewModel){
	"use strict";
	
    var OrdersOverviewCollection = Backbone.Collection.extend({

        model: OrdersOverviewModel,
        url: app.API + "Orders/GetOpenList/",
        parse: function (response) {
            if(response.error) 
            {
                MyPOS.DisplayError(response.errorMessage);                        
                return null;
            }
            else
            {			
                return response.result;			
            }        	
        }

    });
    
    return OrdersOverviewCollection;
});