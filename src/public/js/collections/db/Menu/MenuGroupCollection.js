define([
    "models/db/Menu/MenuGroup"
], function(MenuGroup){
    "use strict";
    
    return class MenuGroupCollection extends Backbone.Collection
    {
        model() { return MenuGroup; }
        url() {return app.API + "DB/Menu/MenuGroup"}
    }
});