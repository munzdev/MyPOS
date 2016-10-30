/** 
 * @desc		stores the POST state and response state of authentication for user
 */
define([
    "app",
    "models/custom/auth/LoginModel",
    "models/custom/auth/AuthUserModel"
], function(app,
            LoginModel,
            AuthUserModel){
    "use strict";
    
    return class Auth 
    {
        constructor() {
            _.bindAll(this, "updateSession");
            
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
                    if(DEBUG) console.log("Autologin failed");
            
                    this.logged_in = false;                
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
                    if(DEBUG)  console.log("SESSION UPDATE SUCCESS", user);
            
                    this.updateSessionUser( user );
                    this.logged_in = true;
                })
                .fail((result) => {
                    if(DEBUG) console.log("SESSION UPDATE FAILED", result);
                });
        }
        
        logout() {
            return this.loginData.destroy()
                .done(() => {
                     this.$(location).attr('href', app.URL);
                });
        }
    }
});