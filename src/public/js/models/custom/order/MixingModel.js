define(function(){
    "use strict";

    var MixingModel = Backbone.Model.extend({

        defaults: function() {
            return {
                menuid: 0,
                menu_groupid: 0,
                name: '',
                price: 0,
                availability: null
            };
        },
    });

    return MixingModel;
});