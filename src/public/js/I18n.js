// Login Model
// ----------

//Includes file dependencies
define(['module'], function(module) {
    "use strict";
    
    return class I18n {
        constructor(externalDoneCallback) {
            _.bindAll(this, "storeTemplate");
            
            this.externalDoneCallback = externalDoneCallback;
            this.language = window.navigator.userLanguage || window.navigator.language;
            
            $.getJSON(require.toUrl('i18n/' + this.language + '.json'))
                    .done(this.storeTemplate)
                    .fail(() => {                        
                        if(this.language.length > 2)
                        {
                            this.language = this.language.substr(0, 2);
                            this.template = $.getJSON(require.toUrl('i18n/' + this.language + '.json'))
                                    .done(this.storeTemplate)
                                    .fail(() => {
                                        this.loadFallbackLanguage();
                                    });
                        }
                        else
                        {
                            this.loadFallbackLanguage();
                        }
                    });
        }
        
        storeTemplate(json)
        {
            this.template = json;
            this.externalDoneCallback();
        }
        
        loadFallbackLanguage()
        {
            this.template = $.getJSON(require.toUrl('i18n/en.json'))
                    .done(this.storeTemplate)
                    .error((xHR, error) => 
                    {
                        alert("Language file couldnt' be loaded! Please reload the App! Error: " + error);
                    });
        }
    }
} );
