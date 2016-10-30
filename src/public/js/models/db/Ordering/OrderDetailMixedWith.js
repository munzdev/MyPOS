define([
    "app"
], function(app){
    "use strict";

    return class OrderDetailMixedWith extends Backbone.Model {

        defaults() {
            return {OrderDetailid: 0,
                    Menuid: 0};
        }

    }
});