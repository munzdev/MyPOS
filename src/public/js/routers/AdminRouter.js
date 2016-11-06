// Mobile Router
// =============

// Includes file dependencies
define([ "routers/BaseRouter",
         /*"views/admin/AdminView",
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
         "views/admin/AdminSizeView",
         "views/admin/AdminSizeModifyView",*/
], function(BaseRouter,
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
            AdminTableModifyView,
            AdminSizeView,
            AdminSizeModifyView) {
    "use strict";
    
    return class AdminRouter extends BaseRouter
    {
        routes() {
            return {
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
                "admin/table/modify/:id": "admin_table_modify",
                "admin/size": "admin_size",
                "admin/size/add": "admin_size_add",
                "admin/size/modify/:id": "admin_size_modify"
            }
        }
        
        admin() {
            this.show(new AdminView());
        }

        admin_event() {
            this.show(new AdminEventView());
        }

        admin_event_add() {
            this.show(new AdminEventModifyDetailView({id: 'new'}));
        }

        admin_event_copy(id) {
            this.show(new AdminEventCopyView({id: id}));
        }

        admin_event_modify_detail(id) {
            this.show(new AdminEventModifyDetailView({id: id}));
        }

        admin_event_modify_menu(id) {
            this.show(new AdminEventModifyMenuView({id: id}));
        }

        admin_event_modify_menu_add(id, groupid) {
            this.show(new AdminEventModifyMenuModifyView({id: id,
                                                          groupid: groupid}));
        }

        admin_event_modify_menu_modify(id, menuid) {
            this.show(new AdminEventModifyMenuModifyView({id: id,
                                                          menuid: menuid}));
        }

        admin_event_modify_distribution(id) {
            this.show(new AdminEventModifyDistributionView({id: id}));
        }

        admin_event_modify_distribution_add(id) {
            this.show(new AdminEventModifyDistributionModifyView({id: id}));
        }

        admin_event_modify_distribution_modify(id, distributions_placeid) {
            this.show(new AdminEventModifyDistributionModifyView({id: id,
                                                                  distributions_placeid: distributions_placeid}));
        }

        admin_event_modify_printer(id) {
            this.show(new AdminEventModifyPrinterView({id: id}));
        }

        admin_event_modify_printer_add(id) {
            this.show(new AdminEventModifyPrinterModifyView({id: id}));
        }

        admin_event_modify_printer_modify(id, events_printerid) {
            this.show(new AdminEventModifyPrinterModifyView({id: id,
                                                             events_printerid: events_printerid}));
        }

        admin_event_modify_user(id) {
            this.show(new AdminEventModifyUserView({id: id}));
        }

        admin_event_modify_user_add(id) {
            this.show(new AdminEventModifyUserModifyView({id: id}));
        }

        admin_event_modify_user_modify(id, events_userid) {
            this.show(new AdminEventModifyUserModifyView({id: id,
                                                          events_userid: events_userid}));
        }

        admin_user() {
            this.show(new AdminUserView());
        }

        admin_user_add() {
            this.show(new AdminUserModifyView({id: 'new'}));
        }

        admin_user_modify(id) {
            this.show(new AdminUserModifyView({id: id}));
        }

        admin_menu() {
            this.show(new AdminMenuView());
        }

        admin_menu_type_add() {
            this.show(new AdminMenuModifyTypeView({id: 'new'}));
        }

        admin_menu_group_add(menu_typeid) {
            this.show(new AdminMenuModifyGroupView({id: 'new',
                                                    menu_typeid: menu_typeid}));
        }

        admin_menu_type_modify(id) {
            this.show(new AdminMenuModifyTypeView({id: id}));
        }

        admin_menu_group_modify(id) {
            this.show(new AdminMenuModifyGroupView({id: id}));
        }

        admin_table() {
            this.show(new AdminTableView());
        }

        admin_table_add() {
            this.show(new AdminTableModifyView({id: 'new'}));
        }

        admin_table_modify(id) {
            this.show(new AdminTableModifyView({id: id}));
        }

        admin_size() {
            this.show(new AdminSizeView());
        }

        admin_size_add() {
            this.show(new AdminSizeModifyView({id: 'new'}));
        }

        admin_size_modify(id) {
            this.show(new AdminSizeModifyView({id: id}));
        }
    }
} );