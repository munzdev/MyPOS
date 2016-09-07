define([
    "app"
], function(app){
    "use strict";

    var TableModel = Backbone.Model.extend({

        defaults: {
            tableid: 0,
            name: '',
            data: ''
        },
    });

    return TableModel;
});