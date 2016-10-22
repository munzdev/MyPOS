define([
    "app",
    "collections/products/SizeCollection",
    "collections/products/ExtraCollection"
], function(app, SizeCollection, ExtraCollection){
    "use strict";

    var MenuModel = Backbone.Model.extend({

        defaults: function() {
            return {
                menuid: 0,
                menu_groupid: 0,
                name: '',
                price: 0,
                availability: null,
                availability_amount: null,
                sizes: new SizeCollection,
                extras: new ExtraCollection
            };
        },

        parse: function(response)
        {
            response.sizes = new SizeCollection(response.sizes, {parse: true});
            response.extras = new ExtraCollection(response.extras, {parse: true});
            return response;
        }

    });

    return MenuModel;
});