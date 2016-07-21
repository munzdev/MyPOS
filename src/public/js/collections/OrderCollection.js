define([
    "app",
    "MyPOS",
    "models/order/TypeModel"
], function(app, MyPOS, TypeModel){
    "use strict";

    var OrderCollection = Backbone.Collection.extend({
    	model: TypeModel,
    	url: app.API + "Orders/GetOrder/",
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

    return OrderCollection;
});