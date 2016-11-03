define([
    "models/db/Menu/MenuPossibleExtra"
], function(MenuPossibleExtra){
    "use strict";
    
    return class MenuPossibleExtraCollection extends app.BaseCollection
    {
        getModel() { return MenuPossibleExtra; }
        url() {return app.API + "DB/Menu/MenuPossibleExtra"}
    }
});