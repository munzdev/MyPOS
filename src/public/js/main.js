"use strict";
    
// Sets the require.js configuration for your application.
require.config( {
    
    // 3rd party script alias names (Easier to type "jquery" than "libs/jquery-1.8.3.min")
    paths: {
        // Core Libraries
        "jquery": "libs/jquery/jquery-2.2.4.min",
        "jquery-dateFormat": "libs/jquery/jquery-dateFormat.min",
        "jquerymobile": "libs/jqueryMobile/jquery.mobile-1.4.5.min",
        "jquerymobile-datebox": "libs/jqueryMobile/jtsage-datebox.min",
        "underscore": "libs/underscore/underscore-min",
        "backbone": "libs/backbone/backbone-min",
        "text": "libs/require/text",
        "localstorage": "libs/backbone/backbone.localStorage-min",
        "wampy": "libs/wampy/wampy",

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
        }
    } // end Shim Configuration
    
} );

// Includes File Dependencies
require([ "I18n",
          "jquery",
          "underscore",
          "backbone",
          "jquerymobile"],
function(I18n) {
             
    // Disabling this will prevent jQuery Mobile from handling hash changes
    $.mobile.hashListeningEnabled = false;
    $.mobile.ajaxEnabled = false;
    $.mobile.pushStateEnabled = false;
    $.mobile.changePage.defaults.changeHash = false;        
    $.ajaxSetup({ cache: false });      // force ajax call on all browsers
             
    var app = window.app = {
        URL : "/",                      // Base application URL
        API : "API/",                   // Base API URL (used by models & collections)
    };
    
    // Enable Multi Language module
    app.i18n = new I18n(() => {
        require(["views"]);
    });
} );