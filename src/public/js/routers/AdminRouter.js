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
         "views/admin/AdminEventModifyMenuModifyView",
         "views/admin/AdminEventModifyDistributionView",
         "views/admin/AdminEventModifyDistributionModifyView",
         "views/admin/AdminEventModifyPrinterView",
         "views/admin/AdminEventModifyPrinterModifyView",
         "views/admin/AdminEventModifyUserView",
         "views/admin/AdminEventModifyUserModifyView",
         "views/admin/AdminUserView",
         "views/admin/AdminUserModifyView",
         "views/admin/AdminMenuView",
         "views/admin/AdminMenuModifyGroupView",
         "views/admin/AdminMenuModifyTypeView",
         "views/admin/AdminTableView",
         "views/admin/AdminTableModifyView",
], function(app,
            BaseRouter,
            AdminView,
            AdminEventView,
            AdminEventCopyView,
            AdminEventModifyDetailView,
            AdminEventModifyMenuView,
            AdminEventModifyMenuModifyView,
            AdminEventModifyDistributionView,
            AdminEventModifyDistributionModifyView,
            AdminEventModifyPrinterView,
            AdminEventModifyPrinterModifyView,
            AdminEventModifyUserView,
            AdminEventModifyUserModifyView,
            AdminUserView,
            AdminUserModifyView,
            AdminMenuView,
            AdminMenuModifyGroupView,
            AdminMenuModifyTypeView,
            AdminTableView,
            AdminTableModifyView) {
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
            "admin/event/modify/:id/menu/add/:groupid": "admin_event_modify_menu_add",
            "admin/event/modify/:id/menu/:menuid": "admin_event_modify_menu_modify",
            "admin/event/modify/:id/distribution": "admin_event_modify_distribution",
            "admin/event/modify/:id/distribution/add": "admin_event_modify_distribution_add",
            "admin/event/modify/:id/distribution/modify/:distributions_placeid": "admin_event_modify_distribution_modify",
            "admin/event/modify/:id/printer": "admin_event_modify_printer",
            "admin/event/modify/:id/printer/add": "admin_event_modify_printer_add",
            "admin/event/modify/:id/printer/modify/:events_printerid": "admin_event_modify_printer_modify",
            "admin/event/modify/:id/user": "admin_event_modify_user",
            "admin/event/modify/:id/user/add": "admin_event_modify_user_add",
            "admin/event/modify/:id/user/modify/:events_userid": "admin_event_modify_user_modify",
            "admin/user": "admin_user",
            "admin/user/add": "admin_user_add",
            "admin/user/modify/:id": "admin_user_modify",
            "admin/menu": "admin_menu",
            "admin/menu/add": "admin_menu_type_add",
            "admin/menu/add/:id": "admin_menu_group_add",
            "admin/menu/modify/type/:id": "admin_menu_type_modify",
            "admin/menu/modify/group/:id": "admin_menu_group_modify",
            "admin/table": "admin_table",
            "admin/table/add": "admin_table_add",
            "admin/table/modify/:id": "admin_table_modify"
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

        admin_event_modify_menu_add: function(id, groupid)
        {
            if(DEBUG) console.log("Admin Event Modify Menu Add", "OK");
            this.show(new AdminEventModifyMenuModifyView({id: id,
                                                          groupid: groupid}));
        },

        admin_event_modify_menu_modify: function(id, menuid)
        {
            if(DEBUG) console.log("Admin Event Modify Menu Modify", "OK");
            this.show(new AdminEventModifyMenuModifyView({id: id,
                                                          menuid: menuid}));
        },

        admin_event_modify_distribution: function(id)
        {
            if(DEBUG) console.log("Admin Event Modify Distribution", "OK");
            this.show(new AdminEventModifyDistributionView({id: id}));
        },

        admin_event_modify_distribution_add: function(id)
        {
            if(DEBUG) console.log("Admin Event Modify Distribution Add", "OK");
            this.show(new AdminEventModifyDistributionModifyView({id: id}));
        },

        admin_event_modify_distribution_modify: function(id, distributions_placeid)
        {
            if(DEBUG) console.log("Admin Event Modify Distribution Modify", "OK");
            this.show(new AdminEventModifyDistributionModifyView({id: id,
                                                                  distributions_placeid: distributions_placeid}));
        },

        admin_event_modify_printer: function(id)
        {
            if(DEBUG) console.log("Admin Event Modify Printer", "OK");
            this.show(new AdminEventModifyPrinterView({id: id}));
        },

        admin_event_modify_printer_add: function(id)
        {
            if(DEBUG) console.log("Admin Event Modify Printer Add", "OK");
            this.show(new AdminEventModifyPrinterModifyView({id: id}));
        },

        admin_event_modify_printer_modify: function(id, events_printerid)
        {
            if(DEBUG) console.log("Admin Event Modify Printer Modify", "OK");
            this.show(new AdminEventModifyPrinterModifyView({id: id,
                                                             events_printerid: events_printerid}));
        },

        admin_event_modify_user: function(id)
        {
            if(DEBUG) console.log("Admin Event Modify User", "OK");
            this.show(new AdminEventModifyUserView({id: id}));
        },

        admin_event_modify_user_add: function(id)
        {
            if(DEBUG) console.log("Admin Event Modify User Add", "OK");
            this.show(new AdminEventModifyUserModifyView({id: id}));
        },

        admin_event_modify_user_modify: function(id, events_userid)
        {
            if(DEBUG) console.log("Admin Event Modify User Modify", "OK");
            this.show(new AdminEventModifyUserModifyView({id: id,
                                                          events_userid: events_userid}));
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

        admin_menu_type_add: function()
        {
            if(DEBUG) console.log("Admin Menu Type Add", "OK");
            this.show(new AdminMenuModifyTypeView({id: 'new'}));
        },

        admin_menu_group_add: function(menu_typeid)
        {
            if(DEBUG) console.log("Admin Menu Group Add", "OK");
            this.show(new AdminMenuModifyGroupView({id: 'new',
                                                    menu_typeid: menu_typeid}));
        },

        admin_menu_type_modify: function(id)
        {
            if(DEBUG) console.log("Admin Menu Type Modify", "OK");
            this.show(new AdminMenuModifyTypeView({id: id}));
        },

        admin_menu_group_modify: function(id)
        {
            if(DEBUG) console.log("Admin Menu Group Modify", "OK");
            this.show(new AdminMenuModifyGroupView({id: id}));
        },

        admin_table: function()
        {
            if(DEBUG) console.log("Admin Table", "OK");
            this.show(new AdminTableView());
        },

        admin_table_add: function()
        {
            if(DEBUG) console.log("Admin Table Add", "OK");
            this.show(new AdminTableModifyView({id: 'new'}));
        },

        admin_table_modify: function(id)
        {
            if(DEBUG) console.log("Admin Table Modify", "OK");
            this.show(new AdminTableModifyView({id: id}));
        }

    } );

    // Returns the Router class
    return AdminRouter;

} );