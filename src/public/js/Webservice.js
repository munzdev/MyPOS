// Login Model
// ----------

//Includes file dependencies
define(["app"], function(app) {
    "use strict";
    
    return class Webservice {
        constructor(action, callback, formData, dataType) {
            _.bindAll(this, "success", "error");

            if(typeof dataType === 'undefined') dataType = 'json';

            this.action = action;
            this.async = true;
            this.formData = formData;
            this.callback = callback;
            this.dataType = dataType;
        }
        
        call() {
            $.ajax({url: app.API + this.action,
                data: this.formData,
                type: 'POST',
                async: this.async,
                dataType: this.dataType,
                success: this.success,
                error: this.error,
                timeout: ((DEBUG) ? 0 : 30000)
            });
        }
        
        success(result)
        {
            if(result.error)
            {
                this.DisplayError(result.errorMessage);
                if(this.callback && 'error' in this.callback) this.callback.error(result);
            }
            else
            {
                if(this.callback && 'success' in this.callback) this.callback.success(result.result);
            }

            if(this.callback && 'complete' in this.callback) this.callback.complete();
        }
        
        error(jqXHR, textStatus, errorThrown)
        {
            this.DisplayError(textStatus + ": " + errorThrown);
            if('ajaxError' in this.callback) this.callback.ajaxError(jqXHR, textStatus, errorThrown);
        }
        
        DisplayError(errorMessage)
        {
            alert(errorMessage);
        }
    }
} );
