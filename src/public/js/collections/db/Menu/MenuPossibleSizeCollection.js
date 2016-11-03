define([
    "models/db/Menu/MenuPossibleSize"
], function(MenuPossibleSize){
    "use strict";
    
    return class MenuPossibleSizeCollection extends app.BaseCollection
    {
        getModel() { return MenuPossibleSize; }
        url() {return app.API + "DB/Menu/MenuPossibleSize"}
    }
});