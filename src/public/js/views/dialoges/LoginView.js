// Login View
// =============

// Includes file dependencies
define([ 'text!templates/dialoges/login.phtml'],
function( Template ) {              
    "use strict";
    
    return class LoginView extends app.DialogView
    {
        events() {
            return {'click #submit': 'doLogin',
                    'keyup #password': 'onPasswordKeyup'};
    	}
        
        initialize() {
            _.bindAll(this, "render");

            this.render();
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

            if(this.$("#username").val() != '' && this.$("#password").val() != '')
            {
                app.auth.login(
                    this.$("#username").val(),
                    this.$("#password").val(),
                    this.$("#rememberMe").is(":checked")
                )
                .done(() => {
                    if(DEBUG) console.log("Login erfolgreich");                                
                })
                .fail((result) => {
                    if(DEBUG) console.log("Login fehlgeschlagen!");
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