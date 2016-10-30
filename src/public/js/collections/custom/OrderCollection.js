define([
    "app",
    "models/order/TypeModel"
], function(app, TypeModel){
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