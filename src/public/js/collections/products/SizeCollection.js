define([
    "app",
    "models/products/SizeModel"
], function(app, SizeModel){
    "use strict";

    var SizeCollection = Backbone.Collection.extend({
        model: SizeModel
    });

    return SizeCollection;
});