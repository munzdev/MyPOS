define([
    "app",
    "models/products/ExtraModel"
], function(app, ExtraModel){
    "use strict";

    var ExtraCollection = Backbone.Collection.extend({
        model: ExtraModel
    });

    return ExtraCollection;
});