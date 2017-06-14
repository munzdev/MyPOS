define(['models/custom/order/OrderInfo',
        'views/helpers/OrderItemsView',
        'text!templates/pages/order-info.phtml'
], function(OrderInfo,
            OrderItemsView,
            Template) {
    "use strict";
    
    return class OrderInfoView extends app.PageView
    {
        initialize(options) {
            let i18n = this.i18n();
            this.render();

            this.orderInfo = new OrderInfo();
            this.orderInfo.set('Orderid', options.orderid);
            
            this.orderItemsView = new OrderItemsView({mode: 'modify',
                                                      skipCounts: true,
                                                      statusInformation: true});            
            
            this.fetchData(this.orderInfo.fetch(), i18n.loading);
        }

        onDataFetched() {
            let t = this.i18n();

            let statusText;
            if (this.orderInfo.get('OrderInProgresses').length == 0) {
                statusText = t.waiting;
            } else if(this.orderInfo.get('InvoiceFinished') == null) {
                statusText = t.inProgress;
            } else {
                statusText = t.finished;
            }

            statusText = statusText + ' - ';

            if (this.orderInfo.get('open') == 0) {
                statusText += '<span style="color: green;">' + t.billed + '</span>';
            } else {
                statusText += '<span style="color: red;">' + t.unbilled + '</span>';
            }

            this.$('#orderid').append(this.orderInfo.get('Orderid'));
            this.$('#table-name').append(this.orderInfo.get('EventTable').get('Name'));
            this.$('#ordertime').append(app.i18n.toDateTime(this.orderInfo.get('Ordertime')));
            this.$('#waiter').append(this.orderInfo.get('User').get('Firstname') + " " + this.orderInfo.get('User').get('Lastname'));
            this.$('#status').append(statusText);

            if (this.orderInfo.get('last_paydate')) {
                this.$('#last-paydate').append(app.i18n.toDateTime(this.orderInfo.get('last_paydate')));
            }

            if (this.orderInfo.get('InvoiceFinished')) {
                this.$('#finished').append(app.i18n.toDateTime(this.orderInfo.get('InvoiceFinished')));
            }

            if (this.orderInfo.get('amountBilled')) {
                this.$('#amount-billed').append(app.i18n.toCurrency('amountBilled'));
            }
            
            this.orderItemsView.orderDetails = this.orderInfo.get('OrderDetails');
            let detailData = this.orderItemsView.render();
            this.$('#details').append(this.orderItemsView.$el);
            
            this.$('#total').text(app.i18n.toCurrency(detailData.totalSumPrice));
        }

        render() {
            this.renderTemplate(Template);
            this.changePage(this);
        }
    }
} );