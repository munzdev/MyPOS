define([
    "models/admin/DistributionPlaceModel"
], function(DistributionPlaceModel){
    "use strict";

    var DistributionPlaceCollection = app.BaseCollection.extend({
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