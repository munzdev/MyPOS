/** https://github.com/alexanderscott/backbone-login/blob/master/public/models/SessionModel.js
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "models/Auth/LoginModel",
    "models/Auth/AuthUserModel"
], function(app,
            LoginModel,
            AuthUserModel){
    "use strict";
    
    return class Auth 
    {
        constructor() {
            this.logged_in = false;
            
            this.authUser = new AuthUserModel();
            this.loginData = new LoginModel();           
        }
        
        updateSessionUser(userData) {
            this.authUser.set(_.pick(userData, _.keys(this.authUser.defaults)));
        }
        
        checkAuth() {            
            return this.loginData.fetch()
                .done(this.updateSession)        
                .fail(() => {
                    this.set({ logged_in : false });
                });
        }
        
        login(username, password, rememberMe) {
            if(!this.loginData.isNew())
                this.loginData.destroy();
            
            this.loginData.set('username', username);
            this.loginData.set('password', password);
            this.loginData.set('rememberMe', rememberMe);
            return this.loginData.save()
                             .done(this.updateSession);
        }
        
        updateSession() {            
            return this.authUser.fetch()
                .done((user) => {
                    this.updateSessionUser( user );
                    this.set({ userid: user.userid, logged_in: true });
                })
        }
        
        logout() {
            return this.loginData.destroy()
                .done(() => {
                     this.$(location).attr('href', app.URL);
                });
        }
    }
});