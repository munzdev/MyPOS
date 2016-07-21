define(function(){
    "use strict";

    var OrderModel = Backbone.Model.extend({

        defaults: {
            menuid: 0,
            amount: 0,
            single_price: 0,
            extra_detail: null,
            amount_payed: 0,
            menuName: '',
            menu_typeid: 0,
            typeName: '',
            sizeName: '',
            selectedExtras: '',
            mixedWith: '',
            currentInvoiceAmount: 0,
            index: 0
        }

    });

    return OrderModel;
});