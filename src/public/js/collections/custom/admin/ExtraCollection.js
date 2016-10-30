define([
    "app",
    "models/product/ExtraModel"
], function(app, ExtraModel){
    "use strict";

    var ExtraCollection = Backbone.Collection.extend({
        model: ExtraModel,
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

    return ExtraCollection;
});