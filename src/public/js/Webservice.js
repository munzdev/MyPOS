// Login Model
// ----------

//Includes file dependencies
define(["app"], function(app) {
    "use strict";
    
    return class Webservice {
        constructor(action, formData = null, type = 'POST', dataType = 'json') {
            this.action = action;
            this.async = true;
            this.type = type;
            this.formData = formData;
            this.dataType = dataType;
        }
        
        call() {
            
            if(window["DEBUG"] == undefined) var DEBUG = false;
            
            return $.ajax({url: app.API + this.action,
                data: this.formData,
                type: this.type,
                async: this.async,
                dataType: this.dataType,
                success: this.success,
                error: this.error,
                timeout: ((DEBUG) ? 0 : 30000)
            });
        }
    }
} );
