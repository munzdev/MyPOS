define([
    "models/distribution/TodoListModel"
], function(TodoListModel){
    "use strict";

    var TodoListCollection = Backbone.Collection.extend({
        model: TodoListModel
    });

    return TodoListCollection;
});