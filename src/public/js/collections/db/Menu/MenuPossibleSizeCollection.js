define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class MenuPossibleSizeCollection extends BaseCollection
    {
        getModel() { return app.models.Menu.MenuPossibleSize; }
        url() {return app.API + "DB/Menu/MenuPossibleSize"}
    }
});