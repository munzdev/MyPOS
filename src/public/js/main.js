"use strict";

if (typeof DEBUG === 'undefined') var DEBUG = true;


// Sets the require.js configuration for your application.
require.config( {
  
	// 3rd party script alias names (Easier to type "jquery" than "libs/jquery-1.8.3.min")
	paths: {
	
		// Core Libraries
		"jquery": "libs/jquery/jquery-2.2.4.min",
		"jquerymobile": "libs/jqueryMobile/jquery.mobile-1.4.5.min",
                "underscore": "libs/underscore/underscore-min",
		"backbone": "libs/backbone/backbone-min",
		"text": "libs/require/text",
		"localstorage": "libs/backbone/backbone.localStorage-min",
			
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
          "jquery", 
          "MyPOS",
          "Webservice",
          "routers/mainRouter",
          "models/SessionModel", 
          "views/dialoges/ErrorDialogView",          
          "jquerymobile" ], 
  function( app, 
		  $, 
		  MyPOS,
		  Webservice,
		  MainRouter, 
		  SessionModel, 
		  ErrorDialogView) {
	
	// Just use GET and POST to support all browsers
    Backbone.emulateHTTP = true;

	// Prevents all anchor click handling
	$.mobile.linkBindingEnabled = false;

	// Disabling this will prevent jQuery Mobile from handling hash changes
	$.mobile.hashListeningEnabled = false;
	
	$.mobile.ajaxEnabled = false;
	$.mobile.pushStateEnabled = false;
	$.mobile.changePage.defaults.changeHash = false;
	
	// Import Constants from API on startup	
	var webservice = new Webservice();
	webservice.action = 'Utility/Constants';
	webservice.callback = {
						success: function(result){
							_.each(result, function(val, key) {
								window[key] = val;
							});
						},
						//-- reload Website on error:
						error: function(result)
						{
							alert("Error! Please reload the site!");
						},
						ajaxError: function()
						{
							alert("Error! Please reload the site!");
						}
	};
	webservice.call();

	// Instantiates a new Backbone.js Mobile Router
	app.router = new MainRouter();
	
    // Error Displaying
    app.error = new ErrorDialogView();
	
	 // Create a new session model and scope it to the app global
    // This will be a singleton, which other modules can access
    app.session = new SessionModel({});         

    // Check the auth status upon initialization,
    // before rendering anything or matching routes
    app.session.checkAuth({

        // Start the backbone routing once we have captured a user's auth status
        complete: function() {        	
            // HTML5 pushState for URLs without hashbangs
            var hasPushstate = !!(window.history && history.pushState);                        
            if(hasPushstate) Backbone.history.start({ pushState: true, root: '/' });
            else Backbone.history.start();        	       	 
        	
        	//Backbone.history.start();
        }
    });

} );