define([
    "app",
    "models/custom/product/GroupModel"
], function(app, GroupModel){
    "use strict";

    var GroupCollection = Backbone.Collection.extend({
        model: GroupModel
    });

    return GroupCollection;
});