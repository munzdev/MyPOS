// Login Model
// ----------

//Includes file dependencies
define(['module'], function(module) {
    "use strict";
    
    return class I18n {
        constructor(externalDoneCallback) {
            this.externalDoneCallback = externalDoneCallback;
            this.language = window.navigator.userLanguage || window.navigator.language;
            
            $.getJSON(require.toUrl('i18n/' + this.language + '.json'))
                    .done((json) => {
                        this.template = json;
                        this.externalDoneCallback();
                    })
                    .fail(() => {                        
                        if(this.language.length > 2)
                        {
                            this.language = this.language.substr(0, 2);
                            this.template = $.getJSON(require.toUrl('i18n/' + this.language + '.json'))
                                    .done((json) => {
                                        this.template = json;
                                        this.externalDoneCallback();
                                    })
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
        
        loadFallbackLanguage()
        {
            this.template = $.getJSON(require.toUrl('i18n/en.json'))
                    .done((json) => {
                        this.template = json;
                        this.externalDoneCallback();
                    })
                    .error((xHR, error) => 
                    {
                        alert("Language file couldnt' be loaded! Please reload the App! Error: " + error);
                    });
        }
    }
} );
