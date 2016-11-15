

//Includes file dependencies
define(["Webservice",
        "Auth",
        "websocket/Chat",
        "websocket/API",
        "routers/GlobalRouter",
        "routers/AdminRouter",          
        "collections/custom/product/ProductCollection",
        "collections/custom/user/UserCollection",
        "views/dialoges/ErrorDialogView",          
        "views/dialoges/OptionsDialogView",
        "views/dialoges/MessagesDialogView"
        ],
function( Webservice,
          Auth,
          Chat,
          API,
          GlobalRouter,
          AdminRouter,
          ProductCollection,
          UserCollection,
          ErrorDialogView,
          OptionsDialogView,
          MessagesDialogView) {        
              
    function initApp()
    {
        let success;
        
        // HTML5 pushState for URLs without hashbangs        
        var hasPushstate = !!(window.history && history.pushState);
        if(hasPushstate) success = Backbone.history.start({ pushState: true, root: app.URL });
        else success = Backbone.history.start();
        
        // if given url was not matched to a route, forward to base page
        if(!success)
            window.location.href = app.URL;
    }
            
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

            // Create Authentication instance
            app.auth = new Auth();
            app.auth.on("login", () => {
                app.ws.api.Connect();
                app.ws.chat.Connect();
                
                app.optionsDialog = new OptionsDialogView({IsAdmin: app.auth.authUser.get('IsAdmin')});
                app.messagesDialog = new MessagesDialogView();                
                
                $.when(app.productList.fetch(),
                       app.userList.fetch()).then(() => {
                            var fragment = Backbone.history.getFragment();
                            
                            if(fragment != "") return;
                           
                            var rights = app.auth.authUser.get('EventUser').get('UserRoles');
                            var hash = null;

                            if(rights & USER_ROLE_ORDER_OVERVIEW)
                            {
                                hash = "order-overview";
                            }
                            else if(rights & USER_ROLE_ORDER_ADD)
                            {
                                hash = "order-new";
                            }
                            else if(rights & USER_ROLE_DISTRIBUTION)
                            {
                                hash = "distribution";
                            }
                            else if(rights & USER_ROLE_MANAGER)
                            {
                                hash = "manager";
                            }
                            else if(app.auth.authUser.get('IsAdmin'))
                            {
                                hash = "admin";
                            }
                            else
                            {
                                app.error.showAlert("Error Loading App", "There was no initial route found! Do you have any permission set?");
                                return;
                            }
                            
                            window.location.href = app.URL + hash;
                       });                
            });
            app.auth.on("logout", () => {
               Backbone.history.navigate(app.URL, { replace: true });
            });
            
            // create a products collection/model for later to fetch
            app.productList = new ProductCollection();
            app.userList = new UserCollection();

            // Init websocket services
            app.ws = {};
            app.ws.chat = new Chat();
            app.ws.api = new API();            

            // Check the auth status upon initialization,
            // before rendering anything or matching routes
            app.auth.checkAuth()
                    .fail(() => {
                        initApp();
                        var fragment = Backbone.history.getFragment();
                        if(fragment != "")
                            window.location.href = app.URL;
                    })
                    .done(initApp);
        })

        .fail(() => {
            app.error.showAlert("Error Loading App", "Please reload the App!");
            initApp();
        })
} );