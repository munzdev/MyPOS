define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class MenuGroupCollection extends BaseCollection
    {
        getModel() { return app.models.Menu.MenuGroup; }
        url() {return app.API + "DB/Menu/MenuGroup"}
    }
});