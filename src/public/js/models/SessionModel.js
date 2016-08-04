/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "models/UserModel",
    "Webservice",
    "MyPOS",
    "collections/ProductCollection",
    "views/dialoges/OptionsDialogView",
    "views/dialoges/MessagesDialogView"
], function(app,
            UserModel,
            Webservice,
            MyPOS,
            ProductCollection,
            OptionsDialogView,
            MessagesDialogView){
    "use strict";

    var SessionModel = Backbone.Model.extend({

        // Initialize with negative/empty defaults
        // These will be overriden after the initial checkAuth
        defaults: {
            logged_in: false,
            userid: ''
        },

        initialize: function(){
            //_.bindAll(this);

            // Singleton user object
            // Access or listen on this throughout any module with app.session.user
            this.user = new UserModel();

            // create a products collection/model for later to fetch
            this.products = new ProductCollection();
        },

        // Fxn to update user attributes after recieving API response
        updateSessionUser: function( userData ){
            this.user.set(_.pick(userData, _.keys(this.user.defaults)));
        },


        /*
         * Check for session from API
         * The API will parse client cookies using its secret token
         * and return a user object if authenticated
         */
        checkAuth: function(callback, args) {
            var self = this;

            var webservice = new Webservice();
            webservice.action = "Users/IsLoggedIn";
            webservice.callback = {
                success: function(result) {
                    if(result == true)
                    {
                        self.updateSession(callback.success, callback.complete);
                    }
                    else
                    {
                        self.set({ logged_in : false });
                        if('error' in callback) callback.error(result);
                        if('complete' in callback) callback.complete();
                    }
                }
            };
            webservice.call();
        },

        login: function(opts, successCallback, errorCallback, args) {
            var self = this;

            var webservice = new Webservice();
            webservice.action = "Users/Login";
            webservice.formData = opts;
            webservice.callback = {
                success: function(result)
                {
                    // if login was successfull
                    if(result) {
                        self.updateSession(successCallback);
                    } else {
                        errorCallback(result);
                    }
                }
            };
            webservice.call();
        },

        updateSession: function(successCallback, completeCallback)
        {
            var self = this;

            var webservice = new Webservice();
            webservice.action = "Users/GetCurrentUser";
            webservice.callback = {
                success: function(user)
                {
                    self.updateSessionUser( user );
                    self.set({ userid: user.userid, logged_in: true });

                    self.optionsDialog = new OptionsDialogView({is_admin: user.is_admin});
                    self.messagesDialog = new MessagesDialogView();

                    app.ws.api.Connect();
                    app.ws.chat.Connect();

                    self.products.fetch({
                        success: function()
                        {
                                if(successCallback)
                                        successCallback(user);
                        },
                        complete: completeCallback
                    });
                },
            };
            webservice.call();
        },

        logout: function(opts, callback, args) {
            var webservice = new Webservice();
            webservice.action = "Users/Logout";
            webservice.callback = {
                success: MyPOS.UnloadWebsite
            };
            webservice.call();
        }

    });

    return SessionModel;
});