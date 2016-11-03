define(function(){
    "use strict";

    var CheckModel = Backbone.Model.extend({

        defaults: {
            orders_details_special_extraid: 0,
            orderid: 0,
            userid: 0,
            nameUser: '',
            nameTable: '',
            menu_groupid: 0,
            nameGroup: '',
            amount: 0,
            single_price: '',
            single_price_modified_by_userid: 0,
            single_price_modified_by: '',
            extra_detail: '',
            verified: 0,
            finished: '',
            availability: 0,
            availability_amount: 0
        },

    });

    return CheckModel;
});