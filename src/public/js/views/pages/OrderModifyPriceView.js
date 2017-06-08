define(['models/custom/order/OrderModify',
        'text!templates/pages/order-modify-price.phtml',
        'text!templates/pages/order-item.phtml',
        'sprintf'
], function(OrderModify,
            Template,
            TemplateItem) {
    "use strict";

    return class OrderModifyPriceView extends app.PageView
    {
        events() {
            return {'click #list .order-item-edit': 'item_edit',
                    'click #submit': 'finish',
                    'click #dialog-continue': 'click_btn_continue',
                    'popupafterclose #success-popup': 'success_popup_close'}
    	}

        initialize(options) {
            this.orderid = options.orderid;
                        
            $.mobile.loading("show");
            
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

        item_edit(event) {
            let index = $(event.currentTarget).attr('data-index');
            let orderDetail = this.orderModify.get('OrderDetails').get({cid: index});

            this.$('#dialog-input').val(orderDetail.get('SinglePrice'));
            this.$('#dialog-continue').attr('data-index', index);

            this.$('#dialog').popup('open');
        }

        click_btn_continue(event) {
            let index = $(event.currentTarget).attr('data-index');
            let orderDetail = this.orderModify.get('OrderDetails').get({cid: index});
            var value = parseFloat(this.$('#dialog-input').val());
            let i18n = this.i18n();

            if(isNaN(value) || value < 0)
            {
                app.error.showAlert(i18n.invalidPrice);
                return;
            }

            value = value.toFixed(2);

            orderDetail.set('SinglePrice', value);

            this.modifications[index] = value;

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
            var itemTemplate = _.template(TemplateItem);

            this.$('#list').empty();

            let counter = 0;
            let totalSumPrice = 0;
            let sortedCategorys = new Map();
            let t = this.i18n();
            let currency = app.i18n.template.currency;

            // Presort the list by categorys
            this.orderModify.get('OrderDetails').each((orderDetail) => {
                let menuid = orderDetail.get('Menuid');
                let key = null;
                
                if(menuid === null && !orderDetail.get('Verified'))
                    return;

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
                this.$('#list').append(divider);
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

                    let datas = {name: isSpecialOrder ? t.specialOrder : menuSearch.Menu.get('Name'),
                                extras: extras,
                                mode: 'modify',
                                amount: orderDetail.get('Amount'),
                                price: price,
                                totalPrice: totalPrice,
                                menuTypeid: menuTypeid,
                                index: orderDetail.cid,
                                isSpecialOrder: isSpecialOrder,
                                edit: true,
                                skipCounts: true,
                                t: app.i18n.template.OrderItem,
                                i18n: app.i18n.template};

                    this.$('#list').append("<li>" + itemTemplate(datas) + "</li>");
                    counter++;
                }
            }

            if(this.oldPrice === undefined) {
                this.oldPrice = parseFloat(totalSumPrice);
                this.$('#total-old').text(this.oldPrice.toFixed(2) + ' ' + currency);
            }

            this.$('#total-new').text(parseFloat(totalSumPrice).toFixed(2) + ' ' + currency);
            this.$('#total-difference').text(parseFloat(totalSumPrice - this.oldPrice).toFixed(2) + ' ' + currency);

            this.$('#list').listview('refresh');
        }

        render() {
            this.renderTemplate(Template);

            this.changePage(this);
        }
    }
} );