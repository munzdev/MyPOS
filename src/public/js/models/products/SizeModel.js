define([
    "app"
], function(app){
    "use strict";

    var SizeModel = Backbone.Model.extend({

        defaults: function() {
            return {
                menu_sizeid: 0,
                menuid: 0,
                name: '',
                factor: 0,
                price: 0
            };
        }

    });

    return SizeModel;
});