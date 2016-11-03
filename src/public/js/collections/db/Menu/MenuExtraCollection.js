define([
    "models/db/Menu/MenuExtra"
], function(MenuExtra){
    "use strict";
    
    return class MenuExtraCollection extends app.BaseCollection
    {
        getModel() { return MenuExtra; }
        url() {return app.API + "DB/Menu/MenuExtra"}
    }
});