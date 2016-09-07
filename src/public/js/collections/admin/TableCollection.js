define([
    "app",
    "models/admin/TableModel"
], function(app, TableModel){
    "use strict";

    var TableCollection = Backbone.Collection.extend({
    	model: TableModel,
    	url: app.API + "Admin/GetTableList/",
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

    return TableCollection;
});