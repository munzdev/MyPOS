define([
    "app",
    "MyPOS",
    "models/products/GroupModel"
], function(app, MyPOS, GroupModel){
	"use strict";

    var GroupCollection = Backbone.Collection.extend({
        model: GroupModel
    });

    return GroupCollection;
});