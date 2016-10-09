define([
    "app",
    "models/admin/DistributionPlaceModel"
], function(app, DistributionPlaceModel){
    "use strict";

    var DistributionPlaceCollection = Backbone.Collection.extend({
    	model: DistributionPlaceModel,
    	url: app.API + "Admin/GetEventDistributionList/",
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

    return DistributionPlaceCollection;
});