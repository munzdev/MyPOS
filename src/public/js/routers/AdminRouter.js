// Mobile Router
// =============

// Includes file dependencies
define([ "app",
         "routers/BaseRouter",
         "views/admin/AdminView",
         "views/admin/AdminEventView",
         "views/admin/AdminEventCopyView",
         "views/admin/AdminEventModifyDetailView",
         "views/admin/AdminEventModifyMenuView",
         "views/admin/AdminEventModifyDistributionView",
         "views/admin/AdminEventModifyPrinterView",
         "views/admin/AdminEventModifyUserView",
         "views/admin/AdminUserView",
         "views/admin/AdminUserModifyView",
         "views/admin/AdminMenuView",
         "views/admin/AdminTableView"
], function(app,
            BaseRouter,
            AdminView,
            AdminEventView,
            AdminEventCopyView,
            AdminEventModifyDetailView,
            AdminEventModifyMenuView,
            AdminEventModifyDistributionView,
            AdminEventModifyPrinterView,
            AdminEventModifyUserView,
            AdminUserView,
            AdminUserModifyView,
            AdminMenuView,
            AdminTableView) {
    "use strict";

    // Extends Backbone.Router
    var AdminRouter = BaseRouter.extend( {

        // Backbone.js Routes
        routes: {

            "admin": "admin",
            "admin/event": "admin_event",
            "admin/event/add": "admin_event_add",
            "admin/event/copy/:id": "admin_event_copy",
            "admin/event/modify/:id/detail": "admin_event_modify_detail",
            "admin/event/modify/:id/menu": "admin_event_modify_menu",
            "admin/event/modify/:id/distribution": "admin_event_modify_distribution",
            "admin/event/modify/:id/printer": "admin_event_modify_printer",
            "admin/event/modify/:id/user": "admin_event_modify_user",
            "admin/user": "admin_user",
            "admin/user/add": "admin_user_add",
            "admin/user/modify/:id": "admin_user_modify",
            "admin/menu": "admin_menu",
            "admin/table": "admin_table"
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
            this.show(new AdminEventModifyDetailView({id: 'new'}));
        },

        admin_event_copy: function(id)
        {
            if(DEBUG) console.log("Admin Event Copy", "OK");
            this.show(new AdminEventCopyView({id: id}));
        },

        admin_event_modify_detail: function(id)
        {
            if(DEBUG) console.log("Admin Event Modify Detail", "OK");
            this.show(new AdminEventModifyDetailView({id: id}));
        },

        admin_event_modify_menu: function(id)
        {
            if(DEBUG) console.log("Admin Event Modify Menu", "OK");
            this.show(new AdminEventModifyMenuView({id: id}));
        },

        admin_event_modify_distribution: function(id)
        {
            if(DEBUG) console.log("Admin Event Modify Distribution", "OK");
            this.show(new AdminEventModifyDistributionView({id: id}));
        },

        admin_event_modify_printer: function(id)
        {
            if(DEBUG) console.log("Admin Event Modify Printer", "OK");
            this.show(new AdminEventModifyPrinterView({id: id}));
        },

        admin_event_modify_user: function(id)
        {
            if(DEBUG) console.log("Admin Event Modify User", "OK");
            this.show(new AdminEventModifyUserView({id: id}));
        },

        admin_user: function()
        {
            if(DEBUG) console.log("Admin User", "OK");
            this.show(new AdminUserView());
        },

        admin_user_add: function()
        {
            if(DEBUG) console.log("Admin User Add", "OK");
            this.show(new AdminUserModifyView({id: 'new'}));
        },

        admin_user_modify: function(id)
        {
            if(DEBUG) console.log("Admin User Modify", "OK");
            this.show(new AdminUserModifyView({id: id}));
        },

        admin_menu: function()
        {
            if(DEBUG) console.log("Admin Menu", "OK");
            this.show(new AdminMenuView());
        },

        admin_table: function()
        {
            if(DEBUG) console.log("Admin Table", "OK");
            this.show(new AdminTableView());
        }

    } );

    // Returns the Router class
    return AdminRouter;

} );