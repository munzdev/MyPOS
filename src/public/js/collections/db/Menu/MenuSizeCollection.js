define(["collections/BaseCollection"
], function(BaseCollection){
    "use strict";

    return class MenuSizeCollection extends BaseCollection
    {
        getModel() { return app.models.Menu.MenuSize; }
        url() {return app.API + "DB/Menu/MenuSize";}
    }
});