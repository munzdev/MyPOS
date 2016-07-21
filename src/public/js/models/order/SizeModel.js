define([
    "models/products/SizeModel",
], function(ProductsSizeModel){
    "use strict";

    var SizeModel = ProductsSizeModel.extend({

        defaults: function() {
            return _.extend({}, ProductsSizeModel.prototype.defaults(), {

            })
        }

    });

    return SizeModel;
});