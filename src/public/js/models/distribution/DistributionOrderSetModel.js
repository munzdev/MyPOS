define([
    "app",
    "collections/distribution/DistributionOrdersDetailsCollection",
    "collections/distribution/DistributionSpecialExtraCollection"
], function(app,
            DistributionOrdersDetailsCollection,
            DistributionSpecialExtraCollection){
    "use strict";

    var DistributionOrderSetModel = Backbone.Model.extend({
        url: app.API + 'Distribution/GetOrder/',
        defaults: function() {
            return {
                orders_details: new DistributionOrdersDetailsCollection,
                order_details_special_extra: new DistributionSpecialExtraCollection
            };
        },

        parse: function(response)
        {
            if(response.error)
            {
                MyPOS.DisplayError(response.errorMessage);
                return null;
    	    }
            else
            {
                if(response.result)
                {
                    response.result.orders_details = new DistributionOrdersDetailsCollection(response.result.orders_details, {parse: true});
                    response.result.order_details_special_extra = new DistributionSpecialExtraCollection(response.result.order_details_special_extra, {parse: true});
                }

                return response.result;
            }
        }

    });

    return DistributionOrderSetModel;
});