define([
    "models/db/Menu/MenuType"
], function(MenuType){
    "use strict";
    
    return class MenuTypeCollection extends app.BaseCollection
    {
        getModel() { return MenuType; }
        url() {return app.API + "DB/Menu/MenuType";}
    }
});