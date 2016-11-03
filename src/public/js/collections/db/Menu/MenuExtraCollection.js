define([
    "models/db/Menu/MenuExtra"
], function(MenuExtra){
    "use strict";
    
    return class MenuExtraCollection extends Backbone.Collection
    {
        model() { return MenuExtra; }
        url() {return app.API + "DB/Menu/MenuExtra"}
    }
});