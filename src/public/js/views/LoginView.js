// Login View
// =============

// Includes file dependencies
define([ "app", "MyPOS", 'text!templates/dialoges/login.phtml'],
function( app, MyPOS, Template ) {
    "use strict";

    // Extends Backbone.View
    var LoginView = Backbone.View.extend( {

    	title: 'login',
    	el: 'body',
        events: {
            'click #login-submit': 'doLogin',
            'keyup #password': 'onPasswordKeyup'
        },

        // The View Constructor
        initialize: function() {
            _.bindAll(this, "render");

            // Listen for session logged_in state changes and re-render
            app.session.on("change:logged_in", this.render);

            if(app.session.get('logged_in'))
            {
                MyPOS.ChangePage("#order-overview");
                return;
            }

            this.render();
        },

        // Renders all of the Category models on the UI
        render: function() {
            MyPOS.RenderDialogeTemplate(this, this.title, Template);

            return this;
        },

        // Allow enter press to trigger login
        onPasswordKeyup: function(evt){
            var k = evt.keyCode || evt.which;

            if (k == 13 && $('#pasword').val() === ''){
                evt.preventDefault();    // prevent enter-press submit when input is empty
            } else if(k == 13){
                evt.preventDefault();
                this.doLogin();
                return false;
            }
        },

        doLogin: function(event) {
            if(event)
                event.preventDefault();

            if(this.$("#username").val() != '' && this.$("#password").val() != '')
            {
                app.session.login({
                    username: this.$("#username").val(),
                    password: this.$("#password").val(),
                    rememberMe: this.$("#rememberMe").val()
                },
                function(user) {
                    if(DEBUG) console.log("SUCCESS", user);
                    MyPOS.ChangePage("#order-overview");
                },
                function(result) {
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

    } );

    // Returns the View class
    return LoginView;

} );