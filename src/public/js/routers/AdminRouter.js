// Mobile Router
// =============

// Includes file dependencies
define([ "app",
         "routers/BaseRouter",
         "views/admin/AdminView",
         "views/admin/AdminEventView",
         "views/admin/AdminEventModifyView",
         "views/admin/AdminEventModifyDetailsView",
         "views/admin/AdminUserView",
         "views/admin/AdminMenuView"
], function(app,
            BaseRouter,
            AdminView,
            AdminEventView,
            AdminEventModifyView,
            AdminEventModifyDetailsView,
            AdminUserView,
            AdminMenuView) {
    "use strict";

    // Extends Backbone.Router
    var AdminRouter = BaseRouter.extend( {

        // Backbone.js Routes
        routes: {

            "admin": "admin",
            "admin/event": "admin_event",
            "admin/event/add": "admin_event_add",
            "admin/event/modify/:id": "admin_event_modify",
            "admin/user": "admin_user",
            "admin/menu": "admin_menu"
        },

        admin: function()
        {
            if(DEBUG) console.log("Admin", "OK");
            this.show(new AdminView());
        },

        admin_event: function()
        {
            if(DEBUG) console.log("Admin Event", "OK");
            this.show(new AdminEventView());
        },

        admin_event_add: function()
        {
            if(DEBUG) console.log("Admin Event Add", "OK");
            this.show(new AdminEventModifyDetailsView({id: 'new'}));
        },

        admin_event_modify: function(id)
        {
            if(DEBUG) console.log("Admin Event Modify", "OK");
            this.show(new AdminEventModifyView({id: id}));
        },

        admin_user: function()
        {
            if(DEBUG) console.log("Admin User", "OK");
            this.show(new AdminUserView());
        },

        admin_menu: function()
        {
            if(DEBUG) console.log("Admin Menu", "OK");
            this.show(new AdminMenuView());
        }

    } );

    // Returns the Router class
    return AdminRouter;

} );