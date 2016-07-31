define([
    "app",
    "models/distribution/TodoListModel"
], function(app, TodoListModel){
    "use strict";

    var TodoListCollection = Backbone.Collection.extend({
        model: TodoListModel
    });

    return TodoListCollection;
});