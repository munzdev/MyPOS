
// Mobile Router
// =============

// Includes file dependencies
define([ "routers/BaseRouter",
         "views/dialoges/LoginView",
         "views/pages/OrderOverviewView",
         /*"views/pages/OrderOverviewSearchView",
         "views/pages/OrderNewView",
         "views/pages/OrderModifyView",
         "views/pages/OrderPayView",
         "views/pages/OrderModifyPriceView",
         "views/pages/OrderInfoView",
         "views/pages/DistributionView",
         "views/pages/ManagerView",
         "views/pages/ManagerCallbackView",
         "views/pages/ManagerCheckView",
         "views/pages/ManagerGroupmessageView",
         "views/pages/ManagerStatisticView"*/
], function(BaseRouter,
            LoginView,
            OrderOverviewView,
            OrderOverviewSearchView,
            OrderNewView,
            OrderModifyView,
            OrderPayView,
            OrderModifyPriceView,
            OrderInfoView,
            DistributionView,
            ManagerView,
            ManagerCallbackView,
            ManagerCheckView,
            ManagerGroupmessageView,
            ManagerStatisticView) {
    "use strict";
    
    return class MainRouter extends BaseRouter
    {
        routes() {
            return {
                // When there is no hash bang on the url, the home method is called
                "": "login",
                "login": "login",
                "error-dialog": "error_dialog",
                "order-new": "order_new",
                "order-overview": "order_overview",
                "order-overview/status/:status(/orderid/:orderid)(/tableNr/:tableNr)(/from/:from)(/to/:to)(/userid/:userid)": "order_overview",
                "order-overview/search/": "order_search_overview",
                "order-modify(/id/:id)(/tableNr/:tableNr)": "order_modify",
                "order-pay/id/:id/tableNr/:tableNr": "order_pay",
                "order-info/id/:id": "order_info",
                "distribution": "distribution",
                "manager": "manager",
                "manager-callback": "manager_callback",
                "manager-check(/verified/:verified)": "manager_check",
                "manager-groupmessage": "manager_groupmessage",
                "manager-statistic": "manager_statistic",
                "order-modify-price/orderid/:id": "order_modify_price"
            }
        }
        
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

        order_search_overview() {
            this.show(new OrderOverviewSearchView());
        }

        order_new() {
            this.show(new OrderNewView());
        }

        order_modify(id, tableNr) {
            this.show(new OrderModifyView({id: id,
                                           tableNr: tableNr}));
        }

        order_pay(id, tableNr) {
            this.show(new OrderPayView({id: id,
                                        tableNr: tableNr}));
        }

        order_info(id) {
            this.show(new OrderInfoView({id: id}));
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
    }
} );