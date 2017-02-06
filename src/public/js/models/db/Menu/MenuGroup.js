define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class MenuGroup extends BaseModel {

        idAttribute() { return 'MenuGroupid'; }

        defaults() {
            return {MenuGroupid: null,
                    MenuTypeid: null,
                    Name: ''};
        }

        parse(response)
        {
            if('MenuType' in response)
            {
                response.MenuType = new app.models.Menu.MenuType(response.MenuType, {parse: true});
            }

            if('Menu' in response)
            {
                response.Menu = new app.collections.Menu.MenuCollection(response.Menu, {parse: true});
            }

            return super.parse(response);
        }

    }
});