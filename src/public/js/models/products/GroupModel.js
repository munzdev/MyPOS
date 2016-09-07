define([
    "app",
    "collections/products/MenuCollection"
], function(app, MenuCollection){
    "use strict";

    var GroupModel = Backbone.Model.extend({

        defaults: function() {
            return {
                menu_groupid: 0,
                menu_typeid: 0,
                name: '',
                menues: new MenuCollection
            };
        },

        parse: function(response)
        {
            response.menues = new MenuCollection(response.menues, {parse: true});
            return response;
        }

    });

    return GroupModel;
});