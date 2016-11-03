define([
    "models/db/Menu/MenuPossibleExtra"
], function(MenuPossibleExtra){
    "use strict";
    
    return class MenuPossibleExtraCollection extends Backbone.Collection
    {
        model() { return MenuPossibleExtra; }
        url() {return app.API + "DB/Menu/MenuPossibleExtra"}
    }
});