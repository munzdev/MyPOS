define([
    "models/product/SizeModel"
], function(SizeModel){
    "use strict";

    var SizeCollection = app.BaseCollection.extend({
        model: SizeModel,
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

    return SizeCollection;
});