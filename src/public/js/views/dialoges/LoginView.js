// Login View
// =============

// Includes file dependencies
define([ "app",
         'text!templates/dialoges/login.phtml'],
function( app,
          Template ) {              
    "use strict";
    
    return class LoginView extends app.DialogView
    {
        events() {
            return {'click #submit': 'doLogin',
                    'keyup #password': 'onPasswordKeyup'};
    	}
        
        initialize() {
            _.bindAll(this, "render");

            // Listen for session logged_in state changes and re-render
            app.session.on("change:logged_in", this.render);

            if(app.session.get('logged_in'))
            {
                this.sendToDefaultPage();
                return;
            }

            this.render();
        }
        
        sendToDefaultPage() {
            var rights = app.session.user.get('user_roles');

            if(rights & USER_ROLE_ORDER_OVERVIEW)
            {
                MyPOS.ChangePage("#order-overview");
            }
            else if(rights & USER_ROLE_ORDER_ADD)
            {
                MyPOS.ChangePage("#order-new");
            }
            else if(rights & USER_ROLE_DISTRIBUTION)
            {
                MyPOS.ChangePage("#distribution");
            }
            else if(rights & USER_ROLE_MANAGER)
            {
                MyPOS.ChangePage("#manager");
            }
            else if(app.session.user.get('is_admin'))
            {
                MyPOS.ChangePage("#admin");
            }
        }
        
        render() {
            this.renderTemplate(Template);            

            return this;
        }
        
        onPasswordKeyup(evt) {
            var k = evt.keyCode || evt.which;

            if (k == 13 && $('#pasword').val() === ''){
                evt.preventDefault();    // prevent enter-press submit when input is empty
            } else if(k == 13){
                evt.preventDefault();
                this.doLogin();
                return false;
            }
        }

        doLogin(event) {
            if(event)
                event.preventDefault();

            var self = this;

            if(this.$("#username").val() != '' && this.$("#password").val() != '')
            {
                app.session.login({
                    username: this.$("#username").val(),
                    password: this.$("#password").val(),
                    rememberMe: this.$("#rememberMe").val()
                })
                .done((user) => {
                    if(DEBUG) console.log("SUCCESS", user);
                    self.sendToDefaultPage();
                })
                .fail((result) => {
                    if(DEBUG) console.log("ERROR", result);
                    app.error.showAlert('Login fehler!', 'Login ist fehlgeschlagen!');
                });
            }
            else
            {
                // Invalid clientside validations thru parsley
                if(DEBUG) console.log("Did not pass clientside validation");
            }
        }
    }
} );