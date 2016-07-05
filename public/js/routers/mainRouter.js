// Mobile Router
// =============

// Includes file dependencies
define([ "app",
         "backbone",
         "models/SessionModel",
         "models/UserModel",
         "views/LoginView",
         "views/OrderOverviewView",
         "views/OrderNewView",
         "views/OrderModifyView",
         "MyPOS"
 ], function( app, Backbone, SessionModel,  UserModel, LoginView, OrderOverviewView, OrderNewView, OrderModifyView, MyPOS ) {
    "use strict";

    // Extends Backbone.Router
    var MainRouter = Backbone.Router.extend( {

        // The Router constructor
        initialize: function() {

            this.loadedViews = [];

        },

        // Backbone.js Routes
        routes: {

            // When there is no hash bang on the url, the home method is called
            "": "login",
            "login": "login",
            "error-dialog": "error_dialog",
            "order-new": "order_new",
            "order-overview": "order_overview",
            "order-modify(/id/:id)(/tableNr/:tableNr)": "order_modify",
            "order-pay/id/:id": "order_pay"
        },

        show: function(view, options){

            // Every page view in the router should need a header.
            // Instead of creating a base parent view, just assign the view to this
            // so we can create it if it doesn't yet exist
            /*if(!this.headerView){
                this.headerView = new HeaderView({});
                this.headerView.setElement($(".header")).render();
            }*/

            // Close and unbind any existing page view
            if(this.currentView && _.isFunction(this.currentView.close)) this.currentView.close();

            // Establish the requested view into scope
            this.currentView = view;

            // Need to be authenticated before rendering view.
            // For cases like a user's settings page where we need to double check against the server.
            if (typeof options !== 'undefined' && options.requiresAuth){
                var self = this;
                app.session.checkAuth({
                    success: function(res){
                        // If auth successful, render inside the page wrapper
                        $('#content').html( self.currentView.render().$el);
                    }, error: function(res){
                        self.navigate("/", { trigger: true, replace: true });
                    }
                });

            } else {

            	if(!this.loadedViews[this.currentView.title])
                {
                    // Render inside the page wrapper
                    //$('#content').html(this.currentView.render().$el);
                    //this.currentView.delegateEvents(this.currentView.events);        // Re-delegate events (unbound when closed)

                    //$('body').append(this.currentView.render());

                    this.loadedViews[this.currentView.title] = this.currentView;
                }

            	// Programatically changes to the current categories page
                $.mobile.changePage( "#" + this.currentView.title);
            }

        },

        login: function() {
            // Fix for non-pushState routing (IE9 and below)
            var hasPushState = !!(window.history && history.pushState);
            if(!hasPushState) this.navigate(window.location.pathname.substring(1), {trigger: true, replace: true});
            else this.show(new LoginView());
        },

        order_overview: function() {
        	if(DEBUG) console.log("Order Overview", "OK");
        	this.show(new OrderOverviewView());
        },

        order_new: function()
        {
        	if(DEBUG) console.log("Order NEW", "OK");
        	this.show(new OrderNewView());
        },

        order_modify: function(id, tableNr)
        {
        	if(DEBUG) console.log("Order MODIFY with id: " + id + " - " + tableNr, "OK");
        	this.show(new OrderModifyView({id: id,
                                               tableNr: tableNr}));
        },

        order_pay: function(id)
        {
            if(DEBUG) console.log("Order PAY with id: " + id, "OK");
        }

    } );

    // Returns the Router class
    return MainRouter;

} );