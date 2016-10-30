define([
    "app",
    "models/db/Menu/MenuSize"
], function(app, MenuSize){
    "use strict";
    
    return class MenuSizeCollection extends Backbone.Collection
    {
        model() { return MenuSize; }
        url() {return app.API + "DB/Menu/MenuSize";}
    }
});