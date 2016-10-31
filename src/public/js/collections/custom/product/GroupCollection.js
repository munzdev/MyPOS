define([
    "models/custom/product/GroupModel",
    "app"
], function(GroupModel){
    "use strict";
    
    return class GroupCollection extends Backbone.Collection
    {
        model() { return GroupModel; }
    }
});