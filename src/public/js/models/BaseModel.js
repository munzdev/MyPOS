define(function(){
    "use strict";
    
    return class BaseModel extends Backbone.Model
    {
         constructor(attributes, options) {
            super(attributes, options);
            
            if(_.isFunction(this.idAttribute))
            {
                this.idAttribute = this.idAttribute();
                
                if(this.idAttribute in this.attributes)
                    this.id = this.get(this.idAttribute);
            }
        }             
    }
});