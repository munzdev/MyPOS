// Mobile Router
// =============

// Includes file dependencies
define([ "app"
], function(app) {
    "use strict";

    // Extends Backbone.Router
    var BaseRouter = Backbone.Router.extend( {

        // The Router constructor
        initialize: function() {

            this.loadedViews = [];

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

    } );

    // Returns the Router class
    return BaseRouter;

} );