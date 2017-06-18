define(['text!templates/helpers/order-item.phtml'
], function(Template) {
    "use strict";

    return class OrderItemsView extends app.RenderView
    {
        jqmAttributes() {
            return {"data-role": "listview",
                    "data-inset": "true"};
        }
        
        tagName() {
            return 'ul';
        }
        
        initialize(options) {
            _.bindAll(this, "order_count_up",
                            "order_count_down",
                            "order_count_up_modify",
                            "order_count_down_modify");
                    
            this.countCallback = options.countCallback;
            this.orderDetails = options.orderDetails;
            this.mode = options.mode;
            this.edit = options.edit;
            this.skipCounts = options.skipCounts;
            this.statusInformation = options.statusInformation;
            this.editCallback = options.editCallback;
        }

        events() {
            return {'click .order-item-edit': 'item_edit',
                    'click #coupon-code-verify': 'verify_coupon',
                    'popupafterclose #select-coupon-popup': 'select_coupon_popup_close'};
        }

        order_count_up(event) {
            if(DEBUG) console.log("Up");

            let index = $(event.currentTarget).attr('data-index');
            let orderDetail = this.orderDetails.get({cid: index});

            var amount_open = orderDetail.get('AmountLeft');
            var current_amount = parseFloat(orderDetail.get('AmountSelected'));
            current_amount++;

            if(current_amount > amount_open && amount_open > 0)
                current_amount = amount_open;
            else if(current_amount > 0 && amount_open < 0)
                current_amount = 0;

            orderDetail.set('AmountSelected', current_amount);

            this.countCallback(orderDetail);
        }

        order_count_down(event) {
            if(DEBUG) console.log("Down");

            let index = $(event.currentTarget).attr('data-index');
            let orderDetail = this.orderDetails.get({cid: index});

            var amount_open = orderDetail.get('AmountLeft');
            var current_amount = parseFloat(orderDetail.get('AmountSelected'));
            current_amount--;

            if((current_amount < 0 && amount_open > 0))
                current_amount = 0;
            else if(current_amount < amount_open && amount_open < 0)
                current_amount = amount_open;

            orderDetail.set('AmountSelected', current_amount);

            this.countCallback(orderDetail);
        }
               
        order_count_up_modify(event) {
            let index = $(event.currentTarget).attr('data-index');

            let orderDetail = this.orderDetails.get({cid: index});
            orderDetail.set('Amount', orderDetail.get('Amount') + 1);

            this.countCallback(orderDetail);
        }

        order_count_down_modify(event) {
            let index = $(event.currentTarget).attr('data-index');

            let orderDetail = this.orderDetails.get({cid: index});
            let newAmount = orderDetail.get('Amount') - 1;

            // If number is allready zero, nothing todo
            if(newAmount == -1) return;

            orderDetail.set('Amount', newAmount);

            this.countCallback(orderDetail);
        }
        
        item_edit(event) {
            let index = $(event.currentTarget).attr('data-index');
            let orderDetail = this.orderDetails.get({cid: index});

            this.editCallback(orderDetail);
        }

        render() {
            var template = _.template(Template);
            
            this.$el.empty();

            let sortedCategorys = new Map();
            var totalSumPrice = 0;
            var totalOpenProducts = 0;
            var totalProductsInInvoice = 0;
            let t = this.i18n();
            
            // Presort the list by categorys
            this.orderDetails.each((orderDetail) => {
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
                this.$el.append(divider);
                let isSpecialOrder = (menuTypeid == null);

                for (let orderDetail of val.orders.values()) {
                    let menuSearch = _.find(app.productList.searchHelper, function(obj) { return obj.Menuid == orderDetail.get('Menuid'); });
                    let extras = '';
                    let singlePrice = parseFloat(orderDetail.get('SinglePrice'));

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
                    
                    if(!orderDetail.get('AmountSelected'))
                        orderDetail.set('AmountSelected', 0);

                    let priceSelected = singlePrice * parseFloat(orderDetail.get('AmountSelected'));

                    totalOpenProducts += orderDetail.get('AmountLeft');
                    totalProductsInInvoice += parseFloat(orderDetail.get('AmountSelected'));

                    let totalPrice = singlePrice * orderDetail.get('Amount');
                    
                    totalSumPrice += this.mode == 'pay' ? priceSelected : totalPrice;

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
                                mode: this.mode,                                
                                price: singlePrice,
                                totalPrice: totalPrice,
                                menuTypeid: menuTypeid,
                                index: orderDetail.cid,
                                isSpecialOrder: isSpecialOrder,
                                skipCounts: (this.mode != 'pay' || orderDetail.get('Verified')) ? this.skipCounts : true,
                                statusInformation: this.statusInformation,
                                rank: "TODO",
                                handled_by_name: "TODO",
                                in_progress_begin: "TODO",
                                in_progress_done: "TODO",
                                amount_recieved_total: "TODO",
                                amount_recieved: "TODO",
                                status: status,
                                edit: orderDetail.get('Verified') ? this.edit : false,
                                t: t,
                                i18n: app.i18n.template};                 
                            
                    if (this.mode == 'pay') {
                        datas.amount = orderDetail.get('AmountSelected') ? orderDetail.get('AmountSelected') : 0;
                        datas.open = orderDetail.get('AmountLeft');
                        datas.totalPrice = priceSelected;
                    } else if(this.mode == 'modify') {
                        datas.amount = orderDetail.get('Amount');
                        datas.totalPrice = totalPrice;
                    }

                    this.$el.append("<li>" + template(datas) + "</li>");
                }
            }

            if (this.mode == 'modify') {
                this.$('.order-item-up').click(this.order_count_up_modify);
                this.$('.order-item-down').click(this.order_count_down_modify);      
            } else {
                this.$('.order-item-up').click(this.order_count_up);
                this.$('.order-item-down').click(this.order_count_down);   
            }
            
            
            this.$el.attr(this.jqmAttributes());            
            this.$el.listview();
            this.delegateEvents();

            // return this;
            return {totalSumPrice: totalSumPrice,
                    totalOpenProducts: totalOpenProducts,
                    totalProductsInInvoice: totalProductsInInvoice}
        }
    }
} );