define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class MenuTypeCollection extends BaseCollection
    {
        getModel() { return app.models.Menu.MenuType; }
        url() {return app.API + "DB/Menu/MenuType";}
    }
});