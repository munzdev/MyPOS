// Mobile Router
// =============

// Includes file dependencies
define(["views/dialoges/LoginView",
        "views/pages/OrderOverviewView",
        "views/pages/OrderOverviewSearchView",
        "views/pages/OrderNewView",
        "views/pages/OrderModifyView",
        "views/pages/OrderModifyPriceView",
        "views/pages/OrderInfoView",
        "views/pages/OrderInvoiceView",
        "views/pages/InvoiceOverviewView",
        "views/pages/InvoiceOverviewSearchView",
        "views/pages/InvoiceInfoView"
        /*"views/pages/DistributionView",
        "views/pages/ManagerView",
        "views/pages/ManagerCallbackView",
        "views/pages/ManagerCheckView",
        "views/pages/ManagerGroupmessageView",
        "views/pages/ManagerStatisticView",
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
        "views/admin/AdminSizeView",
        "views/admin/AdminSizeModifyView",*/
], function(
            //<editor-fold defaultstate="collapsed" desc="Class Definitions">
            LoginView,
            OrderOverviewView,
            OrderOverviewSearchView,
            OrderNewView,
            OrderModifyView,
            OrderModifyPriceView,
            OrderInfoView,
            OrderInvoiceView,
            InvoiceOverviewView,
            InvoiceOverviewSearchView,
            InvoiceInfoView,
            DistributionView,
            ManagerView,
            ManagerCallbackView,
            ManagerCheckView,
            ManagerGroupmessageView,
            ManagerStatisticView,
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
            AdminSizeModifyView
            //</editor-fold>
            ) {
    "use strict";

    return class Router extends Backbone.Router
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

        changeView(view, options) {
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

        routes() {
            return {
                //<editor-fold defaultstate="collapsed" desc="Base Routing">
                // When there is no hash bang on the url, the home method is called
                "": "login",
                    "login": "login",
                    "error-dialog": "error_dialog",
                    "order-new": "order_new",
                    "order-overview": "order_overview",
                    "order-overview/status/:status(/orderid/:orderid)(/tableNr/:tableNr)(/from/:from)(/to/:to)(/userid/:userid)": "order_overview",
                    "order-overview/search/status/:status(/orderid/:orderid)(/tableNr/:tableNr)(/from/:from)(/to/:to)(/userid/:userid)": "order_search_overview",
                    "order-modify(/id/:orderid)(/tableNr/:tableNr)": "order_modify",
                    "order-invoice/id/:orderid": "order_invoice",
                    "order-info/id/:orderid": "order_info",
                    "invoice": "invoice_overview",
                    "invoice/status/:status(/invoiceid/:invoiceid)(/customerid/:customerid)(/canceled/:canceled)(/typeid/:typeid)(/from/:from)(/to/:to)(/userid/:userid)": "invoice_overview",
                    "invoice/search/status/:status(/invoiceid/:invoiceid)(/customerid/:customerid)(/canceled/:canceled)(/typeid/:typeid)(/from/:from)(/to/:to)(/userid/:userid)": "invoice_search_overview",
                    "invoice/id/:invoiceid": "invoice_info",
                    "distribution": "distribution",
                    "manager": "manager",
                    "manager-callback": "manager_callback",
                    "manager-check(/verified/:verified)": "manager_check",
                    "manager-groupmessage": "manager_groupmessage",
                    "manager-statistic": "manager_statistic",
                    "order-modify-price/id/:id": "order_modify_price",
                //</editor-fold>

                //<editor-fold defaultstate="collapsed" desc="Admin Routing">
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
                //</editor-fold>
            }
        }

        //<editor-fold defaultstate="collapsed" desc="Base Routing Functions">
        login() {
            this.show(new LoginView());
        }

        order_overview(status, orderid, tableNr, from, to, userid) {
            if(status)
                this.show(new OrderOverviewView({search: {status: status,
                                                          orderid: orderid,
                                                          tableNr: tableNr,
                                                          from: from,
                                                          to: to,
                                                          userid: userid}
                }));
            else
                this.show(new OrderOverviewView());
        }

        order_search_overview(status, orderid, tableNr, from, to, userid) {
            this.show(new OrderOverviewSearchView({status: status,
                                                   orderid: orderid,
                                                   tableNr: tableNr,
                                                   from: from,
                                                   to: to,
                                                   userid: userid}));
        }

        order_new() {
            this.show(new OrderNewView());
        }

        order_modify(orderid, tableNr) {
            this.show(new OrderModifyView({orderid: orderid,
                                           tableNr: tableNr}));
        }

        order_invoice(orderid) {
            this.show(new OrderInvoiceView({orderid: orderid}));
        }

        order_info(orderid) {
            this.show(new OrderInfoView({orderid: orderid}));
        }

        invoice_overview(status, invoiceid, customerid, canceled, typeid, from, to, userid) {
            if(status)
                this.show(new InvoiceOverviewView({search: {status: status,
                                                            invoiceid: invoiceid,
                                                            customerid: customerid,
                                                            canceled: canceled,
                                                            typeid: typeid,
                                                            from: from,
                                                            to: to,
                                                            userid: userid}
                }));
            else
                this.show(new InvoiceOverviewView());
        }

        invoice_search_overview(status, invoiceid, customerid, canceled, typeid, from, to, userid) {
            this.show(new InvoiceOverviewSearchView({status: status,
                                                     invoiceid: invoiceid,
                                                     customerid: customerid,
                                                     canceled: canceled,
                                                     typeid: typeid,
                                                     from: from,
                                                     to: to,
                                                     userid: userid}));
        }

        invoice_info(invoiceid) {
            this.show(new InvoiceInfoView({invoiceid: invoiceid}));
        }

        distribution() {
            this.show(new DistributionView());
        }

        manager() {
            this.show(new ManagerView());
        }

        manager_callback() {
            this.show(new ManagerCallbackView());
        }

        manager_check(verified) {
            this.show(new ManagerCheckView({verified: verified}));
        }

        manager_groupmessage() {
            this.show(new ManagerGroupmessageView());
        }

        manager_statistic() {
            this.show(new ManagerStatisticView());
        }

        order_modify_price(orderid) {
            this.show(new OrderModifyPriceView({orderid: orderid}));
        }
        //</editor-fold>

        //<editor-fold defaultstate="collapsed" desc="Admin Routing Functions">
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
        //</editor-fold>
    }
} );