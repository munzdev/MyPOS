// Login Model
// ----------

//Includes file dependencies
define(function() {
    "use strict";

    return class I18n {
        constructor(externalDoneCallback) {
            _.bindAll(this, "storeTemplate",
                            "toCurrency");

            this.externalDoneCallback = externalDoneCallback;
            this.language = window.navigator.userLanguage || window.navigator.language;
            this.shortLanguage = this.language.substr(0, 2);

            $.getJSON(require.toUrl('i18n/' + this.language + '.json'))
                    .done(this.storeTemplate)
                    .fail(() => {
                        if(this.language.length > 2)
                        {
                            this.language = this.shortLanguage;
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
            this.language = this.shortLanguage = "en";
            this.template = $.getJSON(require.toUrl('i18n/en.json'))
                    .done(this.storeTemplate)
                    .error((xHR, error) =>
                    {
                        alert("Language file couldnt' be loaded! Please reload the App! Error: " + error);
                    });
        }

        toCurrency(number) {
            return new Intl.NumberFormat(undefined, this.template.IntlCurrency).format(number);
        }

        toDecimal(number) {
            return new Intl.NumberFormat().format(number);
        }

        toDateTime(date) {
            return new Date(date).toLocaleString();
        }

        toDate(date) {
            return new Date(date).toLocaleDateString();
        }

        toTime(date) {
            return new Date(date).toLocaleTimeString();
        }
    }
} );
