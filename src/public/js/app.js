

//Includes file dependencies
define(["Webservice",
        "Auth",
        "websocket/Chat",
        "websocket/API",
        "routers/Router",
        "collections/custom/product/ProductCollection",
        "collections/custom/user/UserCollection",
        "views/dialoges/ErrorDialogView",      
        "views/dialoges/MessagesDialogView",
        "views/helpers/SideMenuView"
        ],
function( Webservice,
          Auth,
          Chat,
          API,
          Router,
          ProductCollection,
          UserCollection,
          ErrorDialogView,
          MessagesDialogView,
          SideMenuView) {        
              
    function initApp()
    {
        let success;
        
        if(app.inited) return;
        
        app.inited = true;
        
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
            app.router = new Router();          

            // Create Authentication instance
            app.auth = new Auth();
            app.auth.on("login", () => {
                app.ws.api.Connect();
                app.ws.chat.Connect();
                
                app.messagesDialog = new MessagesDialogView();                
                app.sideMenu = new SideMenuView();
                
                $.when(app.productList.fetch(),
                       app.user.fetch()).then(() => {
                            initApp();
                           
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
                            else if(rights & USER_ROLE_DISTRIBUTION_OVERVIEW)
                            {
                                hash = "distribution";
                            }
                            else if(rights & USER_ROLE_DISTRIBUTION_PREVIEW)
                            {
                                hash = "distribution-summary";
                            }
                            else if(rights & USER_ROLE_MANAGER_OVERVIEW)
                            {
                                hash = "manager";
                            }
                            else if(rights & USER_ROLE_INVOICE_OVERVIEW)
                            {
                                hash = "invoice";
                            }
                            else if(app.auth.authUser.get('IsAdmin'))
                            {
                                hash = "admin";
                            }
                            else
                            {
                                app.error.showAlert(app.i18n.template.errorLoadingApp, app.i18n.template.errorLoadingAppRouteText);
                                return;
                            }
                            
                            window.location.href = app.URL + hash;
                       });                
            });            
            app.auth.on("pre-logout", () => {
                app.ws.api.Disconnect();
                app.ws.chat.Disconnect();
            });
            app.auth.on("logout", () => {
                app.AbstractView.changeHash("");
            });
            app.auth.on("noAutologin", initApp);
            
            // create a products collection/model for later to fetch
            app.productList = new ProductCollection();
            app.user = new UserCollection();

            // Init websocket services
            app.ws = {};
            app.ws.chat = new Chat();
            app.ws.api = new API();            

            // Check the auth status upon initialization,
            // before rendering anything or matching routes
            app.auth.checkAuth()
                    .fail(() => {
                        var fragment = Backbone.history.getFragment();
                        if(fragment != "")
                            window.location.href = app.URL;
                    })
        })

        .fail(() => {
            app.error.showAlert(app.i18n.template.errorLoadingApp, app.i18n.template.errorLoadingAppText);
            initApp();
        })
} );