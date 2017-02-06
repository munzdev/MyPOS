define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class MenuExtraCollection extends BaseCollection
    {
        getModel() { return app.models.Menu.MenuExtra; }
        url() {return app.API + "DB/Menu/MenuExtra"}
    }
});