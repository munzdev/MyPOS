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
        "views/pages/InvoiceInfoView",
        "views/pages/InvoiceAddView",
        "views/pages/InvoicePaymentView",
        "views/pages/DistributionView",
        "views/pages/DistributionSummaryView",

        "views/manager/OverviewView",
        "views/manager/CallbackView",
        "views/manager/CheckView",
        "views/manager/GroupmessageView",
        "views/manager/StatisticView",

        "views/admin/OverviewView",
        "views/admin/UserView",
        "views/admin/UserModifyView",
        "views/admin/EventView",
        "views/admin/EventCopyView",
        "views/admin/EventModifyView",
        "views/admin/event/OverviewView",
        "views/admin/event/TableView",
        "views/admin/event/TableModifyView",
        /*"views/admin/event/MenuView",
        "views/admin/event/MenuModifyView",
        "views/admin/event/DistributionView",
        "views/admin/event/DistributionModifyView",
        "views/admin/event/PrinterView",
        "views/admin/event/PrinterModifyView",
        "views/admin/event/UserView",
        "views/admin/event/UserModifyView",
        "views/admin/event/MenuTypeView",
        "views/admin/event/GroupView",
        "views/admin/event/TypeView",
        "views/admin/event/SizeView",
        "views/admin/event/SizeModifyView",*/
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
            InvoiceAddView,
            InvoicePaymentView,
            DistributionView,
            DistributionSummaryView,

            ManagerOverviewView,
            ManagerCallbackView,
            ManagerCheckView,
            ManagerGroupmessageView,
            ManagerStatisticView,

            AdminView,
            AdminUserView,
            AdminUserModifyView,
            AdminEventView,
            AdminEventCopyView,
            AdminEventModifyView,
            AdminEventModifyOverviewView,
            AdminEventModifyTableView,
            AdminEventModifyTableModifyView,
            AdminEventModifyMenuView,
            AdminEventModifyMenuModifyView,
            AdminEventModifyDistributionView,
            AdminEventModifyDistributionModifyView,
            AdminEventModifyPrinterView,
            AdminEventModifyPrinterModifyView,
            AdminEventModifyUserView,
            AdminEventModifyUserModifyView,
            AdminMenuView,
            AdminMenuModifyGroupView,
            AdminMenuModifyTypeView,
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
                    "invoice/add": "invoice_add",
                    "invoice/id/:invoiceid": "invoice_info",
                    "invoice/id/:invoiceid/payment": "invoice_add_payment",
                    "distribution": "distribution",
                    "distribution-summary": "distribution_summary",
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
                "admin/event/copy/:eventid": "admin_event_copy",
                "admin/event/:eventid": "admin_event_modify",
                "admin/event/modify/:eventid": "admin_event_modify_overview",
                "admin/event/modify/:eventid/menu": "admin_event_modify_menu",
                "admin/event/modify/:eventid/menu/add/:groupid": "admin_event_modify_menu_add",
                "admin/event/modify/:eventid/menu/:menuid": "admin_event_modify_menu_modify",
                "admin/event/modify/:eventid/distribution": "admin_event_modify_distribution",
                "admin/event/modify/:eventid/distribution/add": "admin_event_modify_distribution_add",
                "admin/event/modify/:eventid/distribution/modify/:distributions_placeid": "admin_event_modify_distribution_modify",
                "admin/event/modify/:eventid/printer": "admin_event_modify_printer",
                "admin/event/modify/:eventid/printer/add": "admin_event_modify_printer_add",
                "admin/event/modify/:eventid/printer/modify/:events_printerid": "admin_event_modify_printer_modify",
                "admin/event/modify/:eventid/user": "admin_event_modify_user",
                "admin/event/modify/:eventid/user/add": "admin_event_modify_user_add",
                "admin/event/modify/:eventid/user/modify/:events_userid": "admin_event_modify_user_modify",
                "admin/event/modify/:eventid/table": "admin_event_modify_table",
                "admin/event/modify/:eventid/table/add": "admin_event_modify_table_add",
                "admin/event/modify/:eventid/table/:id": "admin_event_modify_table_modify",
                "admin/user": "admin_user",
                "admin/user/add": "admin_user_add",
                "admin/user/:userid": "admin_user_modify",
                "admin/menu": "admin_menu",
                "admin/menu/add": "admin_menu_type_add",
                "admin/menu/add/:id": "admin_menu_group_add",
                "admin/menu/modify/type/:id": "admin_menu_type_modify",
                "admin/menu/modify/group/:id": "admin_menu_group_modify",
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

        invoice_add() {
            this.show(new InvoiceAddView());
        }

        invoice_add_payment(invoiceid) {
            this.show(new InvoicePaymentView({invoiceid: invoiceid}));
        }

        distribution() {
            this.show(new DistributionView());
        }
        
        distribution_summary() {
            this.show(new DistributionSummaryView());
        }

        manager() {
            this.show(new ManagerOverviewView());
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
            this.show(new AdminEventModifyView({eventid: 'new'}));
        }

        admin_event_copy(eventid) {
            this.show(new AdminEventCopyView({eventid: eventid}));
        }

        admin_event_modify(eventid) {
            this.show(new AdminEventModifyView({eventid: eventid}));
        }
        
        admin_event_modify_overview(eventid) {
            this.show(new AdminEventModifyOverviewView({eventid: eventid}));
        }

        admin_event_modify_menu(eventid) {
            this.show(new AdminEventModifyMenuView({eventid: eventid}));
        }

        admin_event_modify_menu_add(eventid, groupid) {
            this.show(new AdminEventModifyMenuModifyView({eventid: eventid,
                groupid: groupid}));
        }

        admin_event_modify_menu_modify(eventid, menuid) {
            this.show(new AdminEventModifyMenuModifyView({eventid: eventid,
                menuid: menuid}));
        }

        admin_event_modify_distribution(eventid) {
            this.show(new AdminEventModifyDistributionView({eventid: eventid}));
        }

        admin_event_modify_distribution_add(eventid) {
            this.show(new AdminEventModifyDistributionModifyView({eventid: eventid}));
        }

        admin_event_modify_distribution_modify(eventid, distributions_placeid) {
            this.show(new AdminEventModifyDistributionModifyView({eventid: eventid,
                distributions_placeid: distributions_placeid}));
        }

        admin_event_modify_printer(eventid) {
            this.show(new AdminEventModifyPrinterView({eventid: eventid}));
        }

        admin_event_modify_printer_add(eventid) {
            this.show(new AdminEventModifyPrinterModifyView({eventid: eventid}));
        }

        admin_event_modify_printer_modify(eventid, events_printerid) {
            this.show(new AdminEventModifyPrinterModifyView({eventid: eventid,
                events_printerid: events_printerid}));
        }

        admin_event_modify_user(eventid) {
            this.show(new AdminEventModifyUserView({eventid: eventid}));
        }

        admin_event_modify_user_add(eventid) {
            this.show(new AdminEventModifyUserModifyView({eventid: eventid}));
        }

        admin_event_modify_user_modify(eventid, events_userid) {
            this.show(new AdminEventModifyUserModifyView({eventid: eventid,
                events_userid: events_userid}));
        }

        admin_user() {
            this.show(new AdminUserView());
        }

        admin_user_add() {
            this.show(new AdminUserModifyView({userid: 'new'}));
        }

        admin_user_modify(userid) {
            this.show(new AdminUserModifyView({userid: userid}));
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

        admin_event_modify_table(eventid) {
            this.show(new AdminEventModifyTableView({eventid: eventid}));
        }

        admin_event_modify_table_add(eventid) {
            this.show(new AdminEventModifyTableModifyView({eventid: eventid,
                                                           tableid: 'new'}));
        }

        admin_event_modify_table_modify(eventid, tableid) {
            this.show(new AdminEventModifyTableModifyView({eventid: eventid,
                                                           tableid: tableid}));
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