define([
    "models/db/Menu/Menu",
    "collections/db/Menu/MenuSizeCollection",
    "collections/db/Menu/MenuExtraCollection"
], function(Menu, 
            MenuSizeCollection, 
            MenuExtraCollection){
    "use strict";
    
    return class MenuModel extends Menu
    {
        defaults()
        {
            return _.extend(super.defaults(), {sizes: new MenuSizeCollection,
                                               extras: new MenuExtraCollection});
        }
        
        parse(response)
        {
            response.sizes = new MenuSizeCollection(response.sizes, {parse: true});
            response.extras = new MenuExtraCollection(response.extras, {parse: true});
            return super.response(response);
        }
    }
});