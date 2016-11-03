define([
    "models/db/Menu/MenuType"
], function(MenuType ){
    "use strict";
    
    return class ProductCollection extends app.BaseCollection
    {                
        getModel() { return MenuType; }
  
        url() {return app.API + "Product";}
        
        initialize() {            
            this.searchHelper = [];
        }
        
        fetch(options)
        {
            return super.fetch(options).done(() => {                
                this.each((type) => {
                    type.get('MenuGroup').each((group) =>  {
                        group.get('Menu').each((menu) => {
                            this.searchHelper.push({MenuTypeid: type.get('MenuTypeid'),
                                                    MenuGroupid: group.get('MenuGroupid'),
                                                    Name: type.get('Name'),
                                                    Menuid: menu.get('Menuid'),
                                                    Menu: menu});
                        });
                    });
                });               
            });                                  
        }                
    }
});