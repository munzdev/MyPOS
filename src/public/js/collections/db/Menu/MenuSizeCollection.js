define([
    "models/db/Menu/MenuSize"
], function(MenuSize){
    "use strict";
    
    return class MenuSizeCollection extends app.BaseCollection
    {
        getModel() { return MenuSize; }
        url() {return app.API + "DB/Menu/MenuSize";}
    }
});