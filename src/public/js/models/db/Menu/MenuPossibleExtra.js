define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class MenuPossibleExtra extends BaseModel {

        idAttribute() { return 'MenuPossibleExtraid'; }

        defaults() {
            return {MenuPossibleExtraid: null,
                    MenuExtraid: null,
                    Menuid: null,
                    Price: 0,
                    IsDeleted: null};
        }

        parse(response)
        {
            if('MenuExtra' in response)
            {
                response.MenuExtra = new app.models.Menu.MenuExtra(response.MenuExtra, {parse: true});
            }

            if('Menu' in response)
            {
                response.Menu = new app.models.Menu.Menu(response.Menu, {parse: true});
            }

            return super.parse(response);
        }
    }
});