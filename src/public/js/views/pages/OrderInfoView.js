define(['models/custom/order/OrderInfo',
        'text!templates/pages/order-info.phtml',
        'text!templates/pages/order-item.phtml',
        'jquery-dateFormat'
], function(OrderInfo,
            Template,
            TemplateItem) {
    "use strict";
    
    return class OrderInfoView extends app.PageView
    {
        initialize(options) {
            let i18n = this.i18n();
            this.render();

            this.orderInfo = new OrderInfo();
            this.orderInfo.set('Orderid', options.orderid);
            this.fetchData(this.orderInfo.fetch(), i18n.loading);
        }

        onDataFetched() {
            var itemTemplate = _.template(TemplateItem);

            this.$('#details').empty();

            let counter;
            let totalSumPrice = 0;
            let sortedCategorys = new Map();
            let t = this.i18n();
            let i18n = app.i18n.template;

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

            // Presort the list by categorys
            this.orderInfo.get('OrderDetails').each((orderDetail) => {
                let menuid = orderDetail.get('Menuid');
                let key = null;
                
                if(menuid === null && sortedCategorys.get(key) == null) {
                    sortedCategorys.set(key, {name: t.specialOrders,
                                              orders: new Set()});
                } else if(menuid !== null) {
                    let menuSearch = _.find(app.productList.searchHelper, function(obj) {return obj.Menuid == menuid});                    
                    key = menuSearch.MenuTypeid;
                                        
                    if(sortedCategorys.get(key) == null) {
                        let menuType = app.productList.findWhere({MenuTypeid: key});
                        sortedCategorys.set(key, {name: menuType.get('Name'),
                                                  orders: new Set()});
                    }                                                            
                }
                
                sortedCategorys.get(key).orders.add(orderDetail);
            });

            for(let[menuTypeid, val] of sortedCategorys.entries()) {
                let divider = $('<li/>').attr('data-role', 'list-divider').text(val.name);
                this.$('#details').append(divider);
                counter = 0;
                let isSpecialOrder = (menuTypeid == null);

                for (let orderDetail of val.orders.values()) {
                    let menuSearch = _.find(app.productList.searchHelper, function(obj) { return obj.Menuid == orderDetail.get('Menuid'); });
                    let extras = '';
                    let price = parseFloat(orderDetail.get('SinglePrice'));

                    let menuSize = orderDetail.get('MenuSize');

                    // Add size text if multible sizes are avaible for the product
                    if(!isSpecialOrder && menuSearch.Menu.get('MenuPossibleSize').length > 1)
                        extras = menuSize.get('Name') + ", ";
                    
                    if(orderDetail.get('OrderDetailMixedWiths').length > 0) {
                        extras += t.mixedWith + ": ";

                        orderDetail.get('OrderDetailMixedWiths').each((orderDetailMixedWith) => {
                            let menuToMixWith = _.find(app.productList.searchHelper, function(obj) { return obj.Menuid == orderDetailMixedWith.get('Menuid'); });
                            extras += menuToMixWith.Menu.get('Name') + " - ";
                        });
                        extras = extras.slice(0, -3);
                        extras += ", ";
                    }
                    
                    orderDetail.get('OrderDetailExtras').each(function(extra) {
                        let menuPossibleExtra = menuSearch.Menu.get('MenuPossibleExtra')
                                                                .findWhere({MenuPossibleExtraid: extra.get('MenuPossibleExtraid')});
                        extras += menuPossibleExtra.get('MenuExtra').get('Name') + ", ";
                    });

                    if(orderDetail.get('ExtraDetail') && orderDetail.get('ExtraDetail').length > 0)
                        extras += orderDetail.get('ExtraDetail') + ", ";

                    if(extras.length > 0)
                        extras = extras.slice(0, -2);

                    let totalPrice = price * orderDetail.get('Amount');
                    totalSumPrice += totalPrice;

                    var status = ORDER_STATUS_WAITING;

                    if(false/*originalMenu.get('in_progress_begin') != null*/)
                    {
                        status = ORDER_STATUS_IN_PROGRESS;
                    }
                    if(false/*originalMenu.get('in_progress_done') != null*/)
                    {
                        status = ORDER_STATUS_FINISHED;
                    }

                    // TODO Positions, Status, ... needs to be displayed correctly.
                    var datas = {name: isSpecialOrder ? t.specialOrder : menuSearch.Menu.get('Name'),
                                extras: extras,
                                mode: 'modify',
                                amount: orderDetail.get('Amount'),
                                price: price,
                                totalPrice: totalPrice,
                                menuTypeid: menuTypeid,
                                index: orderDetail.cid,
                                isSpecialOrder: isSpecialOrder,
                                skipCounts: true,
                                statusInformation: true,
                                rank: "TODO",
                                handled_by_name: "TODO",
                                in_progress_begin: "TODO",
                                in_progress_done: "TODO",
                                amount_recieved_total: "TODO",
                                amount_recieved: "TODO",
                                status: status,
                                t: app.i18n.template.OrderItem,
                                i18n: i18n};

                    this.$('#details').append("<li>" + itemTemplate(datas) + "</li>");
                    counter++;
                }
            }

            this.$('#total').text(app.i18n.toCurrency(totalSumPrice));
        }

        render() {
            this.renderTemplate(Template);
            this.changePage(this);
        }
    }
} );