define([
    "models/db/Menu/Menu",
    "models/db/Menu/MenuExtra",
    "app"
], function(Menu,
            MenuExtra){
    "use strict";

    return class MenuPossibleExtra extends Backbone.Model {
        
        idAttribute() { return 'MenuPossibleExtraid'; }

        defaults() {
            return {MenuPossibleExtraid: 0,
                    MenuExtraid: 0,
                    Menuid: 0,
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