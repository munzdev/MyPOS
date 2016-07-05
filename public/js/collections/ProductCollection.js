/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "MyPOS",
    "models/products/TypeModel"
], function(app, MyPOS, TypeModel){
    "use strict";
	
    var ProductCollection = Backbone.Collection.extend({
        initialize: function()
        {
            var self = this;
            
            this.searchHelper = [];
            
            this.on("reset", function() {    	
        	this.each(function(category){
                    category.get('groupes').each(function(groupe){
                        groupe.get('menues').each(function(menu) {
                                self.searchHelper.push({menu_typeid: category.get('menu_typeid'),
                                                        menu_groupid: groupe.get('menu_groupid'),
                                                        name: category.get('name'),
                                                        menuid: menu.get('menuid'),
                                                        menu: menu});
                        });
                    });
        	});
            });
        },
        
        fetch: function(options)
        {
            options.reset = true;
            return Backbone.Collection.prototype.fetch.call(this, options);
        },
        
        model: TypeModel,
        url: app.API + "Products/GetList/",
        parse: function (response) {
            if(response.error)
            {
                MyPOS.DisplayError(response.errorMessage);                        
                return null;
    	    }
            else
            {
                return response.result;			
            }        	
        }
    });
    
    return ProductCollection;
});