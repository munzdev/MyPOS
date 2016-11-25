define([
    "models/db/Menu/Menu",
    "models/db/Menu/MenuSize",
    
], function(Menu,
            MenuSize){
    "use strict";

    return class MenuPossibleSize extends app.BaseModel {
        
        idAttribute() { return 'MenuPossibleSizeid'; }

        defaults() {
            return {MenuPossibleSizeid: null,
                    MenuSizeid: null,
                    Menuid: null,
                    Price: 0};
        }

        parse(response)
        {
            if('MenuSize' in response)
            {
                response.MenuSize = new MenuSize(response.MenuSize, {parse: true});
            }
            
            if('Menu' in response)
            {
                response.Menu = new Menu(response.Menu, {parse: true});
            }
            
            return super.parse(response);
        }
    }
});