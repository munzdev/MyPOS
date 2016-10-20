
// Mobile Router
// =============

// Includes file dependencies
define([ "app",
         "routers/BaseRouter",
         "views/dialoges/LoginView",
         "views/pages/OrderOverviewView",
         "views/pages/OrderOverviewSearchView",
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
         "views/pages/ManagerStatisticView"
], function(app,
            BaseRouter,
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

    // Extends Backbone.Router
    var MainRouter = BaseRouter.extend( {

        // Backbone.js Routes
        routes: {

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
        },

        login: function() {
            // Fix for non-pushState routing (IE9 and below)
            var hasPushState = !!(window.history && history.pushState);
            if(!hasPushState) this.navigate(window.location.pathname.substring(1), {trigger: true, replace: true});
            else this.show(new LoginView());
        },

        order_overview: function(status, orderid, tableNr, from, to, userid) {
            if(DEBUG) console.log("Order Overview", "OK");

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
        },

        order_search_overview: function()
        {
            if(DEBUG) console.log("Order Overview SEARCH", "OK");
            this.show(new OrderOverviewSearchView());
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

        order_pay: function(id, tableNr)
        {
            if(DEBUG) console.log("Order PAY with id: " + id, "OK");
            this.show(new OrderPayView({id: id,
                                        tableNr: tableNr}));
        },

        order_info: function(id)
        {
            if(DEBUG) console.log("Order INFO with id: " + id, "OK");
            this.show(new OrderInfoView({id: id}));
        },

        distribution: function()
        {
            if(DEBUG) console.log("Distriution", "OK");
            this.show(new DistributionView());
        },

        manager: function()
        {
            if(DEBUG) console.log("Manager", "OK");
            this.show(new ManagerView());
        },

        manager_callback: function()
        {
            if(DEBUG) console.log("Manager Callback", "OK");
            this.show(new ManagerCallbackView());
        },

        manager_check: function(verified)
        {
            if(DEBUG) console.log("Manager Check", "OK");
            this.show(new ManagerCheckView({verified: verified}));
        },

        manager_groupmessage: function()
        {
            if(DEBUG) console.log("Manager Groupmessage", "OK");
            this.show(new ManagerGroupmessageView());
        },

        manager_statistic: function()
        {
            if(DEBUG) console.log("Manager Statistic", "OK");
            this.show(new ManagerStatisticView());
        },

        order_modify_price: function(orderid)
        {
            if(DEBUG) console.log("Order Modify Price", "OK");
            this.show(new OrderModifyPriceView({orderid: orderid}));
        }
    } );

    // Returns the Router class
    return MainRouter;

} );