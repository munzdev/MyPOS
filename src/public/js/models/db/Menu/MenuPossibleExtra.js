define([
    "app"
], function(app){
    "use strict";

    return class MenuPossibleExtra extends Backbone.Model {
        
        idAttribute() { return 'MenuPossibleExtraid'; }

        defaults() {
            return {MenuPossibleExtraid: 0,
                    MenuExtraid: 0,
                    Menuid: 0,
                    Price: 0};
        }

    }
});