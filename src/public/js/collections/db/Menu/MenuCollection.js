define([
    "models/db/Menu/Menu"
], function(Menu){
    "use strict";
    
    return class MenuCollection extends Backbone.Collection
    {
        model() { return Menu; }
        url() {return app.API + "DB/Menu";}
    }
});