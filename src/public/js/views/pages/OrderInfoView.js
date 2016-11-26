define(['models/custom/order/OrderInfo',
        'views/helpers/HeaderView',
        'text!templates/pages/order-info.phtml',
        'text!templates/pages/order-item.phtml',
        'jquery-dateFormat'
], function(OrderInfo,
            HeaderView,
            Template,
            TemplateItem) {
    "use strict";
    
    return class OrderInfoView extends app.PageView
    {            
        // The View Constructor
        initialize(options) {
            _.bindAll(this, "render",
                            "renderOrder");

            this.orderInfo = new OrderInfo();
            this.orderInfo.set('Orderid', options.orderid);
            this.orderInfo.fetch()
                          .done(this.render);
        }

        renderOrder() {
            var itemTemplate = _.template(TemplateItem);

            this.$('#details').empty();

            let counter = 0;
            let totalSumPrice = 0;
            let sortedCategorys = new Map();
            let t = this.i18n();
            let i18n = app.i18n.template;
            let currency = i18n.currency;
            
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

            this.$('#total').text(parseFloat(totalSumPrice).toFixed(2) + ' €');
        }

        // Renders all of the Category models on the UI
        render() {
            let header = new HeaderView();
            this.registerSubview(".nav-header", header);
            
            this.renderTemplate(Template, {orderInfo: this.orderInfo});

            this.renderOrder();

            this.changePage(this);
            return this;
        }
    }
} );