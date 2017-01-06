"use strict";

// Sets the require.js configuration for your application.
require.config( {

    // 3rd party script alias names (Easier to type "jquery" than "libs/jquery-1.8.3.min")
    paths: {
        // Core Libraries
        "jquery": "libs/jquery/jquery-2.2.4",
        "jquery-dateFormat": "libs/jquery/jquery-dateFormat",
        "jquery-validate": "libs/jquery/jquery.validate",
        "jquerymobile": "libs/jqueryMobile/jquery.mobile-1.4.5",
        "jquerymobile-datebox": "libs/jqueryMobile/jtsage-datebox",
        "jquerymobile-datebox-lang": "libs/jqueryMobile/jtsage-datebox.lang",
        "underscore": "libs/underscore/underscore",
        "backbone": "libs/backbone/backbone",
        "text": "libs/require/text",
        "wampy": "libs/wampy/wampy",
        "sprintf": "libs/sprintf/sprintf",

        // Core Directorys
        "templates": "../templates",
        "collections": "collections",
        "models": "models",
        "routers": "routers",
        "views": "views"

    },

    // Sets the configuration for your third party scripts that are not AMD compatible
    shim: {
        'underscore': {
            exports: "_"
        },
        "backbone": {
            "deps": [ "underscore", "jquery" ],
            "exports": "Backbone"  //attaches "Backbone" to the window object
        },
        "jquerymobile": {
            "deps": [ "jquery" ]
        },
        "jquerymobile-datebox": {
            "deps": [ "jquerymobile" ]
        },
        "jquerymobile-datebox-lang": {
            "deps": [ "jquerymobile-datebox" ]
        }
    } // end Shim Configuration

} );

// Includes File Dependencies
require([ "I18n",
          "jquery",
          "underscore",
          "backbone",
          "jquerymobile",
          "jquerymobile-datebox-lang"],
function(I18n) {

    // Disabling this will prevent jQuery Mobile from handling hash changes
    $.mobile.hashListeningEnabled = false; // Disable hash listing as Backbone will handle changes
    $.mobile.ajaxEnabled = false; // Dont use Ajax calls
    $.mobile.pushStateEnabled = false; // Dont use Push State listening as this also gets handled by Backbone
    $.mobile.changePage.defaults.changeHash = false;  // Don't change the URLS hash on link clicking. Will be done manuly by code events
    $.mobile.autoInitializePage = false; // As no Page is in the DOM by default, don't try to init them
    $.ajaxSetup({ cache: false });      // force ajax call on all browsers, no caching

    var app = window.app = {
        URL : "/",                      // Base application URL
        API : "API/"                    // Base API URL (used by models & collections)
    };

    // Enable Multi Language module
    app.i18n = new I18n(() => {
        $.extend($.mobile.widgets.datebox.prototype.options, {
                useLang: app.i18n.shortLanguage
        });

        require(["loadBaseClasses"]);
    });
} );