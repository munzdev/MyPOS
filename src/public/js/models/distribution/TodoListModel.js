define([
    "app"
], function(){
    "use strict";

    var TodoListModel = Backbone.Model.extend({
        defaults: {
            tableNr: '',
            ordertime: null,
            amount: 0
        }
    });

    return TodoListModel;
});