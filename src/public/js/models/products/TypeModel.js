define([
    "app",
    "MyPOS",
    "collections/products/GroupCollection"
], function(app, MyPOS, GroupCollection){
	"use strict";

    var TypeModel = Backbone.Model.extend({

        defaults: function() {
        	return {
        		menu_typeid: 0,
        		name: '',
                tax: 0,
                allowMixing: false,
                groupes: new GroupCollection
        	};
        },

        parse: function(response)
        {
            response.groupes = new GroupCollection(response.groupes, {parse: true});
            return response;
        }

    });

    return TypeModel;
});