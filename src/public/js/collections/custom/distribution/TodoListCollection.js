define([
    "models/distribution/TodoListModel"
], function(TodoListModel){
    "use strict";

    var TodoListCollection = app.BaseCollection.extend({
        model: TodoListModel
    });

    return TodoListCollection;
});