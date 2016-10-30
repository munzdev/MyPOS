define([
    "app"
], function(app){
    "use strict";

    return class MenuGroup extends Backbone.Model {
        
        idAttribute() { return 'MenuGroupid'; }

        defaults() {
            return {MenuGroupid: 0,
                    MenuTypeid: 0,
                    Name: ''};
        }

    }
});