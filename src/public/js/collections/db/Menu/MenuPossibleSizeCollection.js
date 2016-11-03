define([
    "models/db/Menu/MenuPossibleSize"
], function(MenuPossibleSize){
    "use strict";
    
    return class MenuPossibleSizeCollection extends Backbone.Collection
    {
        model() { return MenuPossibleSize; }
        url() {return app.API + "DB/Menu/MenuPossibleSize"}
    }
});