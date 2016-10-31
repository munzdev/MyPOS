define([
    "models/db/Menu/Menu",
    "models/db/Menu/MenuSize",
    "app"
], function(Menu,
            MenuSize){
    "use strict";

    return class MenuPossibleSize extends Backbone.Model {
        
        idAttribute() { return 'MenuPossibleSizeid'; }

        defaults() {
            return {MenuPossibleSizeid: 0,
                    MenuSizeid: 0,
                    Menuid: 0,
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