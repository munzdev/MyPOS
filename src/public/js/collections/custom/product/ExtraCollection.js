define([
    "app",
    "models/custom/product/ExtraModel"
], function(app, ExtraModel){
    "use strict";

    var ExtraCollection = Backbone.Collection.extend({
        model: ExtraModel
    });

    return ExtraCollection;
});