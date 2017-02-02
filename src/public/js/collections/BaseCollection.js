define(function(){
    "use strict";

    return class BaseCollection extends Backbone.Collection
    {
         constructor(models, options) {
            super(null, options);

            this.model = this.getModel();

            // dirty hack: In case of dependencie vialation/recursion, model will be empty. Try to fix it
            if(this.model == undefined) {
                this.model = app.BaseModel;
                this.model.extend({idAttrribute: false});
            }

            if (models) this.reset(models, _.extend({silent: true}, options));
        }
    }
});