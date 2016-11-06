// Mobile Router
// =============

// Includes file dependencies
define(function() {
    "use strict";
    
    return class BaseRouter extends Backbone.Router
    {
        show(view, options = {}) {
            // Need to be authenticated before rendering view.
            // For cases like a user's settings page where we need to double check against the server.
            if (options.requiresAuth){
                app.auth.checkAuth()
                        .done(() => {
                            this.changeView(view, options);
                        })
                        .fail(() => {
                            this.navigate(app.URL, {trigger: true, 
                                                    replace: true });
                        });
            }
            else
            {
                this.changeView(view, options);
            }                       
        }
        
        changeView(view, options)
        {
            if(DEBUG) console.log("Change View to " + view.id(), options);
            
            // At this time the current view and the new view are in DOM. 
            // Change the page to make transitions correctly if required
            // and afterwards drop old view from DOM content
            view.changePage(view, options);
            
            // Close and unbind any existing page view
            if(this.currentView && _.isFunction(this.currentView.close) && !options.keepOldView) 
                this.currentView.close();

            // Establish the requested view into scope
            this.currentView = view;
        }
    }
} );