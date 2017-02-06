define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class MenuCollection extends BaseCollection
    {
        getModel() { return app.models.Menu.Menu; }
        url() {return app.API + "DB/Menu";}
    }
});