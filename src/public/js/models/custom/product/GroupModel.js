define([
    "models/db/Menu/MenuGroup",
    "collections/custom/product/MenuCollection"
], function(MenuGroup,
            MenuCollection){
    "use strict";
    
    return class GroupModel extends MenuGroup
    {
        defaults()
        {
            return _.extend(super.defaults(), {menues: new MenuCollection});
        }
        
        parse(response)
        {
            response.menues = new MenuCollection(response.menues, {parse: true});
            return super.response(response);
        }
    }
});