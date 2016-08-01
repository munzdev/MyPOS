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
                order_details_special_extra: new DistributionSpecialExtraCollection,
                orderid: 0,
                tableNr: '',
                orders_in_progressids: []
            };
        },

        parse: function(response)
        {
            response.orders_details = new DistributionOrdersDetailsCollection(response.orders_details, {parse: true});
            response.order_details_special_extra = new DistributionSpecialExtraCollection(response.order_details_special_extra, {parse: true});

            return response;
        }

    });

    return DistributionOrderSetModel;
});