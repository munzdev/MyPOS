define([
    "models/admin/TableModel"
], function( TableModel){
    "use strict";

    var TableCollection = app.BaseCollection.extend({
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