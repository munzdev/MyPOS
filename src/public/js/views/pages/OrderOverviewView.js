// Login View
// =============

// Includes file dependencies
define([ "Webservice",
         'collections/custom/order/OrderOverviewCollection',
         'views/helpers/HeaderView',
         'text!templates/pages/order-overview.phtml'],
 function(  Webservice,
            OrderOverviewCollection,
            HeaderView,
            Template ) {
    "use strict";
    
    return class OrderOverviewView extends app.PageView
    {
        initialize(options) {
            _.bindAll(this, "render",
                            "cancel_order_popup",
                            "cancel_order",
                            "success_popup_close");

            var search = null;

            if(options)
                search = options.search;

            this.ordersList = new OrderOverviewCollection();

            if(search)
                this.ordersList.fetch({data: {search: search},
                                       success: this.render});
            else
            {
                this.ordersList.fetch().done(this.render);
            }
        }
        
        events() {
            return {
                'click .cancel-btn': 'cancel_order_popup',
                'click .pay-btn': 'click_btn_pay',
                'click .info-btn': 'click_btn_info',
                'click .modify-btn': 'click_btn_modify',
                'click #dialog-continue': 'dialog_continue',
                'click #search-btn': 'click_btn_search',
                'click .manage-priority-btn': 'click_btn_priority',
                'click .manage-price-btn': 'click_btn_price',
                'popupafterclose #cancel-success-popup': 'success_popup_close'
            };
        }
        
        cancel_order_popup(event) {
            this.cancelOrderId = $(event.currentTarget).attr('data-order-id');
            this.dialogMode = 'cancel';
            
            let i18n = this.i18n();

            $('#dialog-title').text(i18n.cancelOrder + '?');
            $('#dialog-text').text(i18n.cancelOrderText + '?');
            $('#dialog').popup('open');
        }
        
        dialog_continue() {
            $('#dialog').popup('close')

            if(this.dialogMode == 'cancel')
                this.cancel_order();
            else if(this.dialogMode == 'priority')
                this.set_priority();
        }
        
        cancel_order() {
            var webservice = new Webservice();
            webservice.action = "Orders/MakeCancel";
            webservice.formData = {orderid: this.cancelOrderId};

            webservice.callback = {
                success: function() {
                    $('#cancel-success-popup').popup("open");
                }
            };
            webservice.call();
        }
        
        click_btn_pay(event) {
            var orderid = $(event.currentTarget).attr('data-order-id');
            var tableNr = $(event.currentTarget).attr('data-table-nr');

            this.changeHash("order-invoice/id/" + orderid);
        }

        click_btn_modify(event) {
            var orderid = $(event.currentTarget).attr('data-order-id');
            var tableNr = $(event.currentTarget).attr('data-table-nr');

            this.changeHash("order-modify/id/" + orderid);
        }

        click_btn_info(event) {
            var orderid = $(event.currentTarget).attr('data-order-id');

            this.changeHash("order-info/id/" + orderid);
        }

        click_btn_priority(event) {
            this.priorityOrderId = $(event.currentTarget).attr('data-order-id');
            this.dialogMode = 'priority';
            
            let i18n = this.i18n();

            $('#dialog-title').text(i18n.changePriority + "?");
            $('#dialog-text').text(i18n.changePriorityText + "?");
            $('#dialog').popup('open');
        }

        click_btn_price(event) {
            var orderid = $(event.currentTarget).attr('data-order-id');

            this.changeHash("order-modify-price/orderid/" + orderid);
        }

        set_priority() {
            var webservice = new Webservice();
            webservice.action = "Manager/SetPriority";
            webservice.formData = {orderid: this.priorityOrderId};
            webservice.call().done(this.reload);
        }

        click_btn_search() {
            this.changeHash(Backbone.history.getFragment() + "/search/");
        }

        success_popup_close() {
            this.reload();
        }

        // Renders all of the Category models on the UI
        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);
            
            this.renderTemplate(Template, {orders: this.ordersList,
                                           userRoles: app.auth.authUser.get('EventUser').get('UserRoles')});

            this.changePage(this);

            return this;
        }
    }

} );