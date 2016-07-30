define([
    "app"
], function(){
    "use strict";

    var DistributionOrdersDetailModel = Backbone.Model.extend({
        defaults: {
            orders_detailid: 0,
            amount: 0,
            name: 0,
            menu_sizeid: 0,
            sizeName: 0,
            extra_detail: '',
            extrasName: '',
            mixedWithName: ''
        }
    });

    return DistributionOrdersDetailModel;
});