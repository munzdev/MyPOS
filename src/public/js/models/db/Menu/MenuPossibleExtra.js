define([
    "models/db/Menu/Menu",
    "models/db/Menu/MenuExtra",
    
], function(Menu,
            MenuExtra){
    "use strict";

    return class MenuPossibleExtra extends app.BaseModel {
        
        idAttribute() { return 'MenuPossibleExtraid'; }

        defaults() {
            return {MenuPossibleExtraid: null,
                    MenuExtraid: null,
                    Menuid: null,
                    Price: 0};
        }

        parse(response)
        {
            if('MenuExtra' in response)
            {
                response.MenuExtra = new MenuExtra(response.MenuExtra, {parse: true});
            }
            
            if('Menu' in response)
            {
                response.Menu = new Menu(response.Menu, {parse: true});
            }
            
            return super.parse(response);
        }
    }
});