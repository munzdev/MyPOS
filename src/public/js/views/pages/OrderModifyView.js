// Includes file dependencies
define(["collections/db/Ordering/OrderDetailExtraCollection",
        "collections/db/Ordering/OrderDetailMixedWithCollection",
        "models/custom/order/OrderModify",        
        "models/db/Menu/MenuType",
        "models/db/Ordering/OrderDetail",
        "models/db/Menu/MenuSize",      
        'views/helpers/HeaderView',
        'text!templates/pages/order-modify.phtml',
        'text!templates/pages/order-item.phtml'
], function(OrderDetailExtraCollection,
            OrderDetailMixedWithCollection,
            OrderModify,
            MenuType,
            OrderDetail,
            SizeModel,           
            HeaderView,
            Template,
            TemplateItem) {
    "use strict";

    // Extends Backbone.View
    return class OrderModifyView extends app.PageView
    {
        events() {
            return {'panelbeforeclose .panel': 'order_close',
                    'panelbeforeclose #panel-special': 'order_special_close',

                    // Manually handle events. Ensures that the close event is called after the task
                    'click .add': 'order_add',
                    'click .mixing': 'order_mixing',
                    'click .menu-item': 'menu_item',
                    'click #panel-special-add': 'order_special_add',
                    'click #footer-back': 'footer_back',
                    'click #footer-finish': 'footer_finish',
                    'click #finished': 'finished'}
    	}

        // The View Constructor
        initialize(options) {
            _.bindAll(this, "renderOrder",
                            "order_count_up",
                            "order_count_down",
                            "menu_item",
                            "order_mixing",
                            "order_close",
                            "order_add",
                            "order_special_close",
                            "order_special_add");

            this.options = options;
            this.isMixing = null;

            this.orderModify = new OrderModify();

            if(options.orderid === 'new')
            {
                this.mode = 'new';
                this.orderid = 0;
                this.tableNr = options.tableNr;
            }
            else
            {
                this.mode = 'edit';
                this.orderid = options.orderid;
                this.tableNr = options.tableNr;
                this.orderModify.fetch({data: {id: options.orderid}})
                                            .done(() => {
                                                this.renderOrder();
                                                this.showOverview();
                                            });
            }

            this.render();
        }

        hideTabs() {
            app.productList.each((type) => {
                this.$('#' + type.get('MenuTypeid')).hide();
            });
            this.$('#overview').hide();
        }

        order_add(event) {
            let id = $(event.currentTarget).attr('id');

            let idRegex = /[(0-9)]+/igm;
            let menuid = parseInt(id.match(idRegex)[0]);

            console.log("add " + menuid);

            let menuSearch = _.find(app.productList.searchHelper, function(obj) {return obj.Menuid == menuid});

            let orderDetail = new OrderDetail();
            orderDetail.set("OrderDetailExtra", new OrderDetailExtraCollection());
            orderDetail.set("OrderDetailMixedWith", new OrderDetailMixedWithCollection());

            let extrasSelected = this.$('input[name=extra-' + menuid + ']:checked');

            extrasSelected.each(function(index, extra){

                let menuPossibleExtra = app.productList.findWhere({MenuTypeid: menuSearch.MenuTypeid})
                                                .get('MenuGroup')
                                                .findWhere({MenuGroupid: menuSearch.MenuGroupid})
                                                .get('Menu')
                                                .findWhere({Menuid: menuid})
                                                .get('MenuPossibleExtra')
                                                .findWhere({MenuExtraid: parseInt($(extra).val())});

                let extraModel = new (orderDetail.get("OrderDetailExtra").model);
                extraModel.set("MenuPossibleExtraid", menuPossibleExtra.get("MenuPossibleExtraid"));
                orderDetail.get("OrderDetailExtra").add(extraModel);
            });

            if(menuSearch.Menu.get('MenuPossibleSize').length > 1)
            {
                let sizeSelected = this.$('input[name=size-' + menuid + ']:checked');

                if(sizeSelected.length === 0)
                {
                    alert('Bitte eine größe Auswählen!');
                    return;
                }

                let sizeModel = app.productList.findWhere({MenuTypeid: menuSearch.MenuTypeid})
                                                .get('MenuGroup')
                                                .findWhere({MenuGroupid: menuSearch.MenuGroupid})
                                                .get('Menu')
                                                .findWhere({Menuid: menuid})
                                                .get('MenuPossibleSize')
                                                .findWhere({MenuSizeid: parseInt($(sizeSelected[0]).val())})
                                                .get('MenuSize');

                orderDetail.set('MenuSize', sizeModel);
            }
            else 
            {
                orderDetail.set('MenuSize', menuSearch.Menu.get('MenuPossibleSize').at(0));
            }

            orderDetail.set('Menuid', menuid);
            orderDetail.set('Amount', 1);
            orderDetail.set('SinglePrice', menuSearch.Menu.get('Price'));
            orderDetail.set('ExtraDetail', this.$('#extras-text-' + menuid).val());

            this.$('#panel-mixing-text-' + menuid + ' div').each(function(index, mixing){
                let menuidToMixWith = _.find(app.productList.searchHelper, function(obj) { return obj.Menuid == $(mixing).attr('data-menuid'); });

                let mixingModel = new (orderDetail.get("OrderDetailMixedWith").model);
                mixingModel.set('Menuid', menuidToMixWith.Menu.get('Menuid'));
                orderDetail.get("OrderDetailMixedWith").add(mixingModel);
            });

            this.orderModify.get('OrderDetail').addOnce(orderDetail);

            this.showOverview();
            this.renderOrder();
            this.$('#panel-' + menuid).panel("close");
        }

        showOverview() {
            // Fix jQm Tabs handling as its broken by pushstate (normale simple .tabs(active: last) should work...
            this.hideTabs();
            this.$('#overview').show();
            this.$('#tabs-navbar a.ui-btn-active').removeClass('ui-btn-active');
            this.$('#tabs-navbar-overview').addClass('ui-btn-active');
        }

        order_close(event) {
            console.log("close");

            if(this.isMixing !== null)
                return;

            let id = $(event.currentTarget).attr('id');

            let priceRegex = /[(0-9)]+/igm;
            let menuid = id.match(priceRegex)[0];

            this.$('input[name=size-' + menuid + ']:checked').each(function(){
                $(this).attr('checked', false).checkboxradio("refresh");
            });
            this.$('input[name=extra-' + menuid + ']:checked').each(function(){
                $(this).attr('checked', false).checkboxradio("refresh");
            });
            this.$('#extras-text-' + menuid).val("");
            this.$('#panel-mixing-text-' + menuid).html('');
        }

        order_special_add(event) {
            console.log("add special");

            let specialOrderText = this.$('#panel-special-extra').val();
            specialOrderText = $.trim(specialOrderText);

            if(specialOrderText == '')
            {
                alert('Bitte einen Sonderwunsch eingeben!');
                return;
            }

            let orderDetail = new OrderDetail();
            orderDetail.set("OrderDetailExtra", new OrderDetailExtraCollection());
            orderDetail.set("OrderDetailMixedWith", new OrderDetailMixedWithCollection());
            orderDetail.set('Amount', 1);
            orderDetail.set('ExtraDetail', specialOrderText);

            this.orderModify.get('OrderDetail').addOnce(orderDetail);

            this.renderOrder();
            this.$('#panel-special').panel("close");
        }

        order_special_close(event) {
            console.log("close special");

            this.$('#panel-special-extra').val("");
        }

        order_mixing(event) {
            console.log("mix");

            let id = $(event.currentTarget).attr('id');

            let priceRegex = /[(0-9)]+/igm;
            let menuid = id.match(priceRegex)[0];

            this.isMixing = menuid;
            this.$('#panel-' + menuid).panel("close");
        }

        menu_item(event) {
            console.log("open");

            if(this.isMixing !== null)
            {
                let mixing_menuid = this.isMixing;
                let href = $(event.currentTarget).attr('href');

                let priceRegex = /[(0-9)]+/igm;
                let menuid = href.match(priceRegex)[0];

                let menuSearch = _.find(app.productList.searchHelper, function(obj) { return obj.Menuid == menuid; });

                let canMix = app.productList.findWhere({MenuTypeid: menuSearch.MenuTypeid})
                                            .get("Allowmixing");

                event.preventDefault();
                event.stopPropagation();

                if(mixing_menuid == menuid)
                {
                    app.error.showAlert("Fehler!", "Ausgewähltes Produkt kann nicht mit sich selbst gemixt werden!");
                    return;
                }

                if(canMix == false)
                {
                    app.error.showAlert("Fehler!", "Ausgewähltes Produkt kann nicht gemixt werden!");
                    return;
                }

                this.isMixing = null;

                let currentMixing = this.$('#panel-mixing-text-' + mixing_menuid).html();

                if($.trim(currentMixing) == '')
                    currentMixing = '<b>Gemixt mit:</b>';

                currentMixing += '<div data-menuid="' + menuid + '">' + menuSearch.Menu.get('Name') + '</div>';

                this.$('#panel-mixing-text-' + mixing_menuid).html(currentMixing);

                this.$('#panel-' + mixing_menuid).panel("open");
            }
        }

        order_count_up(event) {
            console.log("Up");

            let index = $(event.currentTarget).attr('data-index');

            let orderDetail = this.orderModify.get('OrderDetail').get({cid: index});
            orderDetail.set('Amount', orderDetail.get('Amount') + 1);

            this.renderOrder();
        }

        order_count_down(event) {
            console.log("Down");

            let index = $(event.currentTarget).attr('data-index');
            
            let orderDetail = this.orderModify.get('OrderDetail').get({cid: index});
            let newAmount = orderDetail.get('Amount') - 1;

            // If number is allready zero, nothing todo
            if(newAmount == -1) return;

            orderDetail.set('Amount', newAmount);

            this.renderOrder();
        }

        footer_back(event) {
            console.log("back");

            event.preventDefault();
            window.history.back();
        }

        footer_finish(event) {
            console.log("order verify");

            this.$('#verify-dialog').popup('open');
        }

        finished(event) {
            console.log("order finish");

            let self = this;

            let webservice = new Webservice();

            if(this.mode == 'new')
                webservice.action = "Orders/AddOrder";
            else
                webservice.action = "Orders/ModifyOrder";

            webservice.formData = {order: JSON.stringify(this.orderModify.toJSON()),
                                   options: this.options};
            webservice.callback = {
                success: function(result) {
                    let hasSpecialOrders = false;

                    self.order.each(function(category) {
                        if(!hasSpecialOrders)
                        {
                            let isSpecialOrder = category.get('menu_typeid') == "0";
                            hasSpecialOrders = isSpecialOrder;
                        }
                    });

                    if(hasSpecialOrders)
                        app.ws.api.Trigger("manager-check");

                    app.ws.api.Trigger("distribution-update");

                    this.changeHash("order-pay/id/" + result + "/tableNr/" + self.options.tableNr);
                }
            };
            webservice.call();
        }

        renderOrder() {
            let itemTemplate = _.template(TemplateItem);

            this.$('#selected').empty();

            let counter = 0;
            let totalSumPrice = 0;
            let sortedCategorys = new Map();
            
            // Presort the list by categorys
            this.orderModify.get('OrderDetail').each((orderDetail) => {
                let menuid = orderDetail.get('Menuid');
                let key = null;
                
                if(menuid == 0 && sortedCategorys.get(key) == null)
                {
                    sortedCategorys.set(key, {name: "Sonderwünsche",
                                              orders: new Set()});
                }
                else if(menuid != 0)
                {
                    let menuSearch = _.find(app.productList.searchHelper, function(obj) {return obj.Menuid == menuid});                    
                    key = menuSearch.MenuTypeid;
                                        
                    if(sortedCategorys.get(key) == null)
                    {
                        let menuType = app.productList.findWhere({MenuTypeid: key});
                        sortedCategorys.set(key, {name: menuType.get('Name'),
                                                  orders: new Set()});
                    }                                                            
                }
                
                sortedCategorys.get(key).orders.add(orderDetail);
            });

            for(let[menuTypeid, val] of sortedCategorys.entries())
            {
                this.$('#selected').append("<li data-role='list-divider'>" + val.name + "</li>");
                counter = 0;
                let isSpecialOrder = (menuTypeid == null);

                for (let orderDetail of val.orders.values()) {
                    let menuSearch = _.find(app.productList.searchHelper, function(obj) { return obj.Menuid == orderDetail.get('Menuid'); });
                    let extras = '';
                    let price = parseFloat(orderDetail.get('SinglePrice'));
                    let isNew = orderDetail.isNew();

                    let menuSize = orderDetail.get('MenuSize');

                    // Add size text if multible sizes are avaible for the product
                    if(!isSpecialOrder && menuSearch.Menu.get('MenuPossibleSize').length > 1)
                    {
                        extras = menuSize.get('Name') + ", ";
                    }
                    if(isNew && !isSpecialOrder) price += parseFloat(menuSearch.Menu.get('MenuPossibleSize').findWhere({MenuSizeid: menuSize.get('MenuSizeid')}).get('Price'));

                    if(orderDetail.get('OrderDetailMixedWith').length > 0)
                    {
                        extras += "Gemischt mit: ";

                        orderDetail.get('OrderDetailMixedWith').each((orderDetailMixedWith) => {
                            let menuToMixWith = _.find(app.productList.searchHelper, function(obj) { return obj.Menuid == orderDetailMixedWith.get('Menuid'); });
                            extras += menuToMixWith.Menu.get('Name') + " - ";

                            let sizesOfMenuToMixWith = app.productList.findWhere({MenuTypeid: menuToMixWith.MenuTypeid})
                                                                        .get('MenuGroup')
                                                                        .findWhere({MenuGroupid: menuToMixWith.MenuGroupid})
                                                                        .get('Menu')
                                                                        .findWhere({Menuid: menuToMixWith.Menuid})
                                                                        .get('MenuPossibleSize');

                            // -- Price calculation --
                            // First: try to find the same size for the mixing product and get this price
                            let menuToMixWithHasSameSizeAsOriginal = sizesOfMenuToMixWith.findWhere({MenuSizeid: menuSize.get('MenuSizeid')});
                            let menuToMixWithDefaultPrice = parseFloat(menuToMixWith.Menu.get('Price'));

                            if(menuToMixWithHasSameSizeAsOriginal)
                            {
                                let priceToAdd = menuToMixWithDefaultPrice + parseFloat(menuToMixWithHasSameSizeAsOriginal.get('Price'));

                                if(DEBUG) console.log("Mixing same size found: " + priceToAdd);

                                if(isNew) price += priceToAdd;
                                return;
                            }

                            // Second: Try to calculate the price based on factor value
                            let menuToMixWithSize = menuToMixWith.Menu.get('MenuPossibleSize').at(0);

                            let factor = menuSize.get('Factor') / menuToMixWithSize.get('MenuSize').get('Factor');

                            let priceToAdd = (menuToMixWithDefaultPrice + parseFloat(menuToMixWithSize.get('Price')) ) * factor;

                            if(DEBUG) console.log("Mixing factor calculation: " + priceToAdd + " - " + priceToAdd.toFixed(1));

                            if(isNew) price += priceToAdd;
                            // -- End Price calculation --
                        });

                        if(isNew)
                        {
                            price = parseFloat( ( price / (orderDetail.get('OrderDetailMixedWith').length + 1) ) );
                            price = Math.round(price * 10)/10;// avoid peanuts
                        }

                        extras = extras.slice(0, -3);
                        extras += ", ";
                    }

                    orderDetail.get('OrderDetailExtra').each(function(extra){
                        let menuPossibleExtra = menuSearch.Menu.get('MenuPossibleExtra')
                                                                .findWhere({MenuPossibleExtraid: extra.get('MenuPossibleExtraid')});
                        extras += menuPossibleExtra.get('MenuExtra').get('Name') + ", ";
                        if(isNew) price += parseFloat(menuPossibleExtra.get('Price'));
                    });

                    if(orderDetail.get('ExtraDetail') && orderDetail.get('ExtraDetail').length > 0)
                        extras += orderDetail.get('ExtraDetail') + ", ";

                    if(extras.length > 0)
                        extras = extras.slice(0, -2);

                    let totalPrice = price * orderDetail.get('Amount');
                    totalSumPrice += totalPrice;

                    let datas = {name: isSpecialOrder ? "Sonderwunsch" : menuSearch.Menu.get('Name'),
                                extras: extras,
                                mode: 'modify',
                                amount: orderDetail.get('Amount'),
                                price: price,
                                totalPrice: totalPrice,
                                menuTypeid: menuTypeid,
                                index: orderDetail.cid,
                                isSpecialOrder: isSpecialOrder,
                                skipCounts: false};

                    this.$('#selected').append("<li>" + itemTemplate(datas) + "</li>");
                    counter++;
                }             
            }

            if(this.mode == 'edit' && this.oldPrice === undefined)
            {
                this.oldPrice = parseFloat(totalSumPrice);
                this.$('#total-old').text(this.oldPrice.toFixed(2) + ' €');
            }

            if(this.mode == 'new')
            {
                this.$('#total').text(parseFloat(totalSumPrice).toFixed(2) + ' €');
            }
            else
            {
                this.$('#total-new').text(parseFloat(totalSumPrice).toFixed(2) + ' €');
                this.$('#total-difference').text(parseFloat(totalSumPrice - this.oldPrice).toFixed(2) + ' €');
            }

            this.$('.order-item-up').click(this.order_count_up);
            this.$('.order-item-down').click(this.order_count_down);
            this.$('#selected').listview('refresh');
        }

        // Renders all of the Category models on the UI
        render() {
            let header = new HeaderView();
            this.registerSubview(".nav-header", header);
            
            this.renderTemplate(Template, { header: header.render(),
                                            mode: this.mode,
                                            order: this.orderModify,
                                            products: app.productList});
                                        
            // Broken Tabs widget with Backbone pushstate enabled  - manual fix it
            this.hideTabs();            
            this.$("#tabs a[data-role='tab']").click((event) => {
                this.hideTabs();
                this.$($(event.currentTarget).attr('href')).show();
            });

            this.changePage(this);

            return this;
        }
    }    
} );