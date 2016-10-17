/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "models/UserModel",
    "Webservice",
    "MyPOS",
    "collections/ProductCollection",
    "collections/UserCollection",
    "views/dialoges/OptionsDialogView",
    "views/dialoges/MessagesDialogView"
], function(app,
            UserModel,
            Webservice,
            MyPOS,
            ProductCollection,
            UserCollection,
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
            this.userList = new UserCollection();
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
        checkAuth: function() {
            var self = this;

            var webservice = new Webservice();
            webservice.action = "Users/Login";
            webservice.type = "OPTIONS";
            return webservice.call()
                .done(self.updateSession)        
                .fail(() => {
                    self.set({ logged_in : false });
                });
        },

        login: function(opts) {
            var webservice = new Webservice();
            webservice.action = "Users/Login";
            webservice.formData = opts;
            return webservice.call().done(this.updateSession);
        },

        updateSession: function()
        {
            var webservice = new Webservice();
            webservice.action = "Users/Login";
            webservice.type = "GET";
            return webservice.call()
                .done((user) => {
                    this.updateSessionUser( user );
                    this.set({ userid: user.userid, logged_in: true });

                    this.optionsDialog = new OptionsDialogView({is_admin: user.is_admin});
                    this.messagesDialog = new MessagesDialogView();

                    app.ws.api.Connect();
                    app.ws.chat.Connect();
                })
                .then(this.userList.fetch)
                .then(this.products.fetch);
        },

        logout: function() {
            var webservice = new Webservice();
            webservice.action = "Users/Logout";            
            webservice.call().done(MyPOS.UnloadWebsite);
        }

    });

    return SessionModel;
});