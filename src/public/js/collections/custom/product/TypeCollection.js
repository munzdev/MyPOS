define([
    "app",
    "models/custom/product/TypeModel"
], function(app, TypeModel){
    "use strict";
    
    return class TypeCollection extends Backbone.Collection
    {
        model() { return TypeModel; }
        url() {return app.API + "Product";}
        
        initialize() {
            this.searchHelper = [];
            
            this.on("reset", this.onReset);
        }
        
        onReset()
        {
            this.each((type) => {
                type.get('group').each((group) =>  {
                    group.get('menu').each((menu) => {
                        this.searchHelper.push({menu_typeid: type.get('menu_typeid'),
                                                menu_groupid: group.get('menu_groupid'),
                                                name: type.get('name'),
                                                menuid: menu.get('menuid'),
                                                menu: menu});
                    });
                });
            });
        }
        
        fetch(options)
        {
            options = _.extend(options, {reset: true});
            return super.fetch(options);
        }                
    }
});