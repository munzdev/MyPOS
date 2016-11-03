define([
    "models/db/Menu/Menu"
], function(Menu){
    "use strict";
    
    return class MenuCollection extends app.BaseCollection
    {
        getModel() { return Menu; }
        url() {return app.API + "DB/Menu";}
    }
});