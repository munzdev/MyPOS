define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class MenuPossibleExtraCollection extends BaseCollection
    {
        getModel() { return app.models.Menu.MenuPossibleExtra; }
        url() {return app.API + "DB/Menu/MenuPossibleExtra"}
    }
});