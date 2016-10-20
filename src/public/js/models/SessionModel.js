/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "models/LoginModel",
    "models/UserModel",
    "Webservice",
    "collections/ProductCollection",
    "collections/UserCollection",
    "views/dialoges/OptionsDialogView",
    "views/dialoges/MessagesDialogView"
], function(app,
            LoginModel,
            UserModel,
            Webservice,            
            ProductCollection,
            UserCollection,
            OptionsDialogView,
            MessagesDialogView){
    "use strict";
    
    return class SessionModel extends Backbone.Model
    {
        initialize() {
            this.user = new UserModel();
            this.login = new LoginModel();

            // create a products collection/model for later to fetch
            this.products = new ProductCollection();
            this.userList = new UserCollection();
        }
        
        defaults() {
            return {
                logged_in: false,
                userid: ''
            }; 
        }
        
        updateSessionUser(userData) {
            this.user.set(_.pick(userData, _.keys(this.user.defaults)));
        }
        
        checkAuth() {
            var webservice = new Webservice();
            webservice.action = "Users/Login";
            webservice.type = "OPTIONS";
            return webservice.call()
                .done(this.updateSession)        
                .fail(() => {
                    this.set({ logged_in : false });
                });
        }
        
        login(username, password, rememberMe) {
            if(!this.login.isNew())
                this.login.destroy();
            
            this.login.set('username', username);
            this.login.set('password', password);
            this.login.set('rememberMe', rememberMe);
            this.login.save()
                .done(this.updateSession);
        }
        
        updateSession() {
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
        }
        
        logout() {
            this.login.destroy()
                .done(MyPOS.UnloadWebsite);
        }
    }
});