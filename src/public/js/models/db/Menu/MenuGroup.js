define([
    "models/db/Menu/MenuType",
    "collections/db/Menu/MenuCollection"
], function(MenuType,
            MenuCollection){
    "use strict";

    return class MenuGroup extends app.BaseModel {
        
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
                response.MenuType = new MenuType(response.MenuType, {parse: true});
            }
            
            if('Menu' in response)
            {
                response.Menu = new MenuCollection(response.Menu, {parse: true});
            }
            
            return super.parse(response);
        }

    }
});