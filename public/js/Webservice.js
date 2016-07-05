// Login Model
// ----------

//Includes file dependencies
define(["app", "jquery"], function(app) {
    "use strict";

    function Webservice(action, callback, formData, dataType)
    {
        _.bindAll(this, "success", "error");

        if(typeof dataType === 'undefined') dataType = 'json';

        this.action = action;
        this.async = true;
        this.formData = formData;
        this.callback = callback;
        this.dataType = dataType;
    }

    Webservice.prototype.call = function()
    {
        $.ajax({url: app.API + this.action + '/',
                data: this.formData,
                type: 'post',
                async: this.async,
                dataType: this.dataType,
                success: this.success,
                error: this.error,
                timeout: 30000
        });
    }

    Webservice.prototype.success = function(result)
    {
        if(result.error)
        {
            this.DisplayError(result.errorMessage);
            if('error' in this.callback) this.callback.error(result);
        }
        else
        {
            if('success' in this.callback) this.callback.success(result.result);
        }

        if('complete' in this.callback) this.callback.complete();
    }

    Webservice.prototype.error = function(jqXHR, textStatus, errorThrown)
    {
        this.DisplayError(textStatus + ": " + errorThrown);
        if('ajaxError' in this.callback) this.callback.ajaxError(jqXHR, textStatus, errorThrown);
    }

    Webservice.prototype.DisplayError = function(errorMessage)
    {
        alert(errorMessage);
    }

    return Webservice;
} );
