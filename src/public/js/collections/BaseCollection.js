define(function(){
    "use strict";
    
    return class BaseCollection extends Backbone.Collection
    {
         constructor(models, options) {
            super(null, options);

            this.model = this.getModel();
            
            if (models) this.reset(models, _.extend({silent: true}, options));
        }             
    }
});