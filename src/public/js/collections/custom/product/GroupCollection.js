define([
    "models/custom/product/GroupModel"
], function(GroupModel){
    "use strict";
    
    return class GroupCollection extends Backbone.Collection
    {
        model() { return GroupModel; }
    }
});