define([
    "app"
], function(app){
    "use strict";

    var ExtraModel = Backbone.Model.extend({

        defaults: function() {
            return {
                menu_extraid: 0,
                menuid: 0,
                name: '',
                price: 0,
                availability: null,
                availability_amount: null
            };
        }

    });

    return ExtraModel;
});