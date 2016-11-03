define([
    "models/db/Menu/MenuGroup"
], function(MenuGroup){
    "use strict";
    
    return class MenuGroupCollection extends app.BaseCollection
    {
        getModel() { return MenuGroup; }
        url() {return app.API + "DB/Menu/MenuGroup"}
    }
});