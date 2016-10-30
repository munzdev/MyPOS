define([
    "app",
    "models/db/Menu/MenuType"
], function(app, MenuType){
    "use strict";
    
    return class MenuTypeCollection extends Backbone.Collection
    {
        model() { return MenuType; }
        url() {return app.API + "DB/Menu/MenuType";}
    }
});