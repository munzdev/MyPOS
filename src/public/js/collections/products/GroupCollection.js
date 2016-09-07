define([
    "app",
    "models/products/GroupModel"
], function(app, GroupModel){
    "use strict";

    var GroupCollection = Backbone.Collection.extend({
        model: GroupModel
    });

    return GroupCollection;
});