define([
    "models/order/MixingModel"
], function(MixingModel){
    "use strict";

    var MixingCollection = app.BaseCollection.extend({
        model: MixingModel
    });

    return MixingCollection;
});