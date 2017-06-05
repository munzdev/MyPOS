define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class MenuPossibleSize extends BaseModel {

        idAttribute() { return 'MenuPossibleSizeid'; }

        defaults() {
            return {MenuPossibleSizeid: null,
                    MenuSizeid: null,
                    Menuid: null,
                    Price: 0,
                    IsDeleted: null};
        }

        parse(response)
        {
            if('MenuSize' in response)
            {
                response.MenuSize = new app.models.Menu.MenuSize(response.MenuSize, {parse: true});
            }

            if('Menu' in response)
            {
                response.Menu = new app.models.Menu.Menu(response.Menu, {parse: true});
            }

            return super.parse(response);
        }
    }
});