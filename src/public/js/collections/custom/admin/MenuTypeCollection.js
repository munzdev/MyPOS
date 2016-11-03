define([
    "models/product/TypeModel"
], function(TypeModel){
    "use strict";

    var MenuTypeCollection = app.BaseCollection.extend({
        model: TypeModel,
        url: app.API + "Admin/GetMenuList/",
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

    return MenuTypeCollection;
});