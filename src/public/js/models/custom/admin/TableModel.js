define(function(){
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