define(['models/custom/order/OrderModify',
        'views/helpers/OrderItemsView',
        'text!templates/pages/order-modify-price.phtml',
        'sprintf'
], function(OrderModify,
            OrderItemsView,
            Template) {
    "use strict";

    return class OrderModifyPriceView extends app.PageView
    {
        events() {
            return {'click #submit': 'finish',
                    'click #dialog-continue': 'click_btn_continue',
                    'popupafterclose #success-popup': 'success_popup_close'}
    	}

        initialize(options) {
            this.orderid = options.orderid;
                        
            $.mobile.loading("show");
            
            this.orderItemsView = new OrderItemsView({mode: 'modify',
                                                      skipCounts: true,
                                                      edit: true,
                                                      statusInformation: false,
                                                      editCallback: this.item_edit.bind(this)});  
            
            this.orderModify = new OrderModify();
            this.orderModify.set('Orderid', options.orderid);                        
            this.orderModify.fetch()
                            .done(() => {                     
                                $.mobile.loading("hide");
                                this.render();
                                this.renderOpenOrders();
                            });

            this.modifications = {};
        }

        item_edit(orderDetail) {
            this.$('#dialog-input').val(orderDetail.get('SinglePrice'));
            this.editItem = orderDetail;

            this.$('#dialog').popup('open');
        }

        click_btn_continue(event) {
            let orderDetail = this.editItem;
            var value = parseFloat(this.$('#dialog-input').val());
            let i18n = this.i18n();

            if(isNaN(value) || value < 0)
            {
                app.error.showAlert(i18n.invalidPrice);
                return;
            }

            value = value.toFixed(2);

            orderDetail.set('SinglePrice', value);

            this.modifications[orderDetail.get('OrderDetailid')] = value;

            this.$('#dialog').popup('close');
            this.renderOpenOrders();
        }

        finish() {
            let i18n = this.i18n();
            let modifiedOrders = new Set();
            
            _.each(this.modifications, (value, index) => {
                let orderDetail = this.orderModify.get('OrderDetails').get({cid: index}).clone();
                orderDetail.set('SinglePrice', value);
                modifiedOrders.add(orderDetail);
            });

            this.orderModify.save({PriceModifications: 1,
                                   Modifications: Array.from(modifiedOrders)}, {patch: true})
                                    .done(() => {
                                        app.ws.chat.SystemMessage(this.orderModify.get('Userid'), sprintf(i18n.chatMessageInfo, {orderid: this.orderid,
                                                                                                                                name: app.auth.authUser.get('Firstname') + ' ' + app.auth.authUser.get('Lastname')}));
                                        this.$('#success-popup').popup("open");
                                    });
        }

        success_popup_close() {
            this.changeHash("order-overview");
        }

        renderOpenOrders() {
            let t = this.i18n();

            this.$('#list').empty();
            
            this.orderItemsView.orderDetails = this.orderModify.get('OrderDetails');
            let detailData = this.orderItemsView.render();
            let totalSumPrice = detailData.totalSumPrice;
            this.$('#list').append(this.orderItemsView.$el);         

            if(this.oldPrice === undefined) {
                this.oldPrice = parseFloat(totalSumPrice);
                this.$('#total-old').text(app.i18n.toCurrency(this.oldPrice));
            }

            this.$('#total-new').text(app.i18n.toCurrency(totalSumPrice));
            this.$('#total-difference').text(app.i18n.toCurrency(totalSumPrice - this.oldPrice));
        }

        render() {
            this.renderTemplate(Template);

            this.changePage(this);
        }
    }
} );