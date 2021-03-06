define([
    "app",
    "models/products/TypeModel"
], function(app, TypeModel){
    "use strict";

    var MenuTypeCollection = Backbone.Collection.extend({
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