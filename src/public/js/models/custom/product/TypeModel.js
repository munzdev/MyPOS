define([
    "models/db/Menu/MenuType",
    "collections/custom/product/GroupCollection",
    "app"
], function(MenuType, 
            GroupCollection){
    "use strict";
    
    return class TypeModel extends MenuType
    {
        defaults()
        {
            return _.extend(super.defaults(), {groupes: new GroupCollection});
        }
        
        parse(response)
        {
            response.groupes = new GroupCollection(response.groupes, {parse: true});
            return super.response(response);
        }
    }
});