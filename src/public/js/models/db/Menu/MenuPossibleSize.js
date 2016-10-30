define([
    "app"
], function(app){
    "use strict";

    return class MenuPossibleSize extends Backbone.Model {
        
        idAttribute() { return 'MenuPossibleSizeid'; }

        defaults() {
            return {MenuPossibleSizeid: 0,
                    MenuSizeid: 0,
                    Menuid: 0,
                    Price: 0};
        }

    }
});