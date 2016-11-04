

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
          WsChat,
          WsAPI,
          GlobalRouter,
          AdminRouter,
          ProductCollection,
          UserCollection,
          ErrorDialogView,
          OptionsDialogView,
          MessagesDialogView) {        

    // Global event aggregator
    app.eventAggregator = _.extend({}, Backbone.Events);
            
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
                
                $.when(app.products.fetch(),
                       app.userList.fetch()).then(() => {
                            var rights = app.auth.authUser.get('EventUser').get('UserRoles');
                            var view = null;

                            if(rights & USER_ROLE_ORDER_OVERVIEW)
                            {
                                view = "#order-overview";
                            }
                            else if(rights & USER_ROLE_ORDER_ADD)
                            {
                                view = "#order-new";
                            }
                            else if(rights & USER_ROLE_DISTRIBUTION)
                            {
                                view = "#distribution";
                            }
                            else if(rights & USER_ROLE_MANAGER)
                            {
                                view = "#manager";
                            }
                            else if(app.session.user.get('is_admin'))
                            {
                                view = "#admin";
                            }

                            Backbone.history.navigate(view, true);
                       });                
            });
            
            // create a products collection/model for later to fetch
            app.products = new ProductCollection();
            app.userList = new UserCollection();

            // Init websocket services
            app.ws = {};
            app.ws.chat = new WsChat();
            app.ws.api = new WsAPI();            

            // Check the auth status upon initialization,
            // before rendering anything or matching routes
            app.auth.checkAuth();
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