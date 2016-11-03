define([
    "models/db/Menu/MenuType",
    "collections/db/Menu/MenuCollection"
], function(MenuType,
            MenuCollection){
    "use strict";

    return class MenuGroup extends Backbone.Model {
        
        idAttribute() { return 'MenuGroupid'; }

        defaults() {
            return {MenuGroupid: 0,
                    MenuTypeid: 0,
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