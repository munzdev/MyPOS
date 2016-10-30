define([
    "app"
], function(app){
    "use strict";

    return class OrderDetailExtra extends Backbone.Model {

        defaults() {
            return {OrderDetailid: 0,
                    MenuPossibleExtraid: 0};
        }

    }
});