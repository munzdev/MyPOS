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
require([ "app",
          "Webservice",
          "websocket/Chat",
          "websocket/API",
          "routers/GlobalRouter",
          "routers/AdminRouter",
          "models/SessionModel",
          "views/dialoges/ErrorDialogView",
          "jquerymobile",      
          "jquery"],
  function( app,
            Webservice,
            WsChat,
            WsAPI,
            GlobalRouter,
            AdminRouter,
            SessionModel,
            ErrorDialogView) {
    
    // Disabling this will prevent jQuery Mobile from handling hash changes
    $.mobile.hashListeningEnabled = false;
    $.mobile.ajaxEnabled = false;
    $.mobile.pushStateEnabled = false;
    $.mobile.changePage.defaults.changeHash = false;
    
    // Error Displaying
    app.error = new ErrorDialogView();

    // Import Constants from API on startup
    var webservice = new Webservice();
    webservice.action = 'Utility/Constants';
    webservice.call()
        .done((result) => {
            _.each(result, function(val, key) {
                Object.defineProperty(typeof global === "object" ? global : window, key, {
                    value:        val,
                    enumerable:   true,
                    writable:     false,
                    configurable: false
                })
            });  

            // Instantiates a new Backbone.js Mobile Router
            app.routers = {};
            app.routers.global = new GlobalRouter();
            app.routers.admin = new AdminRouter();           

            // Create a new session model and scope it to the app global
            // This will be a singleton, which other modules can access
            app.session = new SessionModel({});

            // Init websocket services
            app.ws = {};
            app.ws.chat = new WsChat();
            app.ws.api = new WsAPI();

            // Check the auth status upon initialization,
            // before rendering anything or matching routes
            app.session.checkAuth()            
                .fail(() => {
                    app.error.showAlert("Error Loading App", "Please reload the App!");
                });
        })

        .fail(() => {
            app.error.showAlert("Error Loading App", "Please reload the App!");
        })
        
        .always(() => {
            // HTML5 pushState for URLs without hashbangs
            var hasPushstate = !!(window.history && history.pushState);
            if(hasPushstate) Backbone.history.start({ pushState: true, root: '/' });
            else Backbone.history.start();
        });
} );