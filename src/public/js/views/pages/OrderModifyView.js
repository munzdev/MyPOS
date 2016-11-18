// Includes file dependencies
define(["Webservice",
        "collections/custom/order/OrderModifyCollection",
        "models/db/Menu/MenuType",
        "models/db/Menu/Menu",
        "models/db/Menu/MenuSize",
        "models/db/Ordering/OrderDetailMixedWith",        
        'views/helpers/HeaderView',
        'text!templates/pages/order-modify.phtml',
        'text!templates/pages/order-item.phtml'
], function(Webservice,
            OrderModifyCollection,
            MenuType,
            Menu,
            SizeModel,
            OrderDetailMixedWith,            
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

            this.order = new OrderModifyCollection();

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
                this.order.fetch({data: {orderid: options.orderid}})
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
            var id = $(event.currentTarget).attr('id');

            var idRegex = /[(0-9)]+/igm;
            var menuid = id.match(idRegex)[0];

            console.log("add " + menuid);

            var menuSearch = _.find(app.productList.searchHelper, function(obj) { return obj.menuid === menuid; });
            var menu_typeid = menuSearch.menu_typeid;

            var group = this.order.findWhere({menu_typeid: menu_typeid});

            if(!group)
            {
                group = new MenuType({menu_typeid: menu_typeid,
                                       name: menuSearch.name});

                this.order.add(group);
            }

            var menu = new Menu(menuSearch.menu.attributes);

            var extras = menu.get('extras').clone();
            extras.reset();

            var extrasSelected = this.$('input[name=extra-' + menuid + ']:checked');

            extrasSelected.each(function(index, extra){

                var extraModel = app.productList.findWhere({menu_typeid: menuSearch.menu_typeid})
                                                .get('groupes')
                                                .findWhere({menu_groupid: menuSearch.menu_groupid})
                                                .get('menues')
                                                .findWhere({menuid: menuid})
                                                .get('extras')
                                                .findWhere({menu_extraid: $(extra).val()});

                extras.add(extraModel);
            });


            var sizes = menu.get('sizes').clone();

            if(sizes.length > 1)
            {
                sizes.reset();

                var sizeSelected = this.$('input[name=size-' + menuid + ']:checked');

                if(sizeSelected.length === 0)
                {
                    alert('Bitte eine größe Auswählen!');
                    return;
                }

                var sizeModel = app.productList.findWhere({menu_typeid: menuSearch.menu_typeid})
                                                .get('groupes')
                                                .findWhere({menu_groupid: menuSearch.menu_groupid})
                                                .get('menues')
                                                .findWhere({menuid: menuid})
                                                .get('sizes')
                                                .findWhere({menu_sizeid: $(sizeSelected[0]).val()});

                sizes.add(sizeModel);
            }

            menu.set('amount', 1);
            menu.set('extras', extras);
            menu.set('sizes', sizes);
            menu.set('extra', this.$('#extras-text-' + menuid).val());

            this.$('#panel-mixing-text-' + menuid + ' div').each(function(index, mixing){
                var mixingReference = _.find(app.productList.searchHelper, function(obj) { return obj.menuid == $(mixing).attr('data-menuid'); });

                var mixingModel = new OrderDetailMixedWith(mixingReference.menu.attributes);

                menu.get('mixing').add(mixingModel);
            });

            group.get('orders').addOnce(menu);

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

            var id = $(event.currentTarget).attr('id');

            var priceRegex = /[(0-9)]+/igm;
            var menuid = id.match(priceRegex)[0];

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

            var specialOrderText = this.$('#panel-special-extra').val();
            specialOrderText = $.trim(specialOrderText);

            if(specialOrderText == '')
            {
                alert('Bitte einen Sonderwunsch eingeben!');
                return;
            }

            var group = this.order.findWhere({menu_typeid: "0"});

            if(!group)
            {
                group = new MenuType({menu_typeid: "0",
                                       name: "Sonderwünsche"});

                this.order.add(group);
            }

            var menu = new Menu();
            menu.set('name', 'Sonderwunsch');
            menu.set('amount', 1);
            menu.set('extra', specialOrderText);

            menu.get('sizes').add(new SizeModel);

            group.get('orders').addOnce(menu);

            this.renderOrder();
            this.$('#panel-special').panel("close");
        }

        order_special_close(event) {
            console.log("close special");

            this.$('#panel-special-extra').val("");
        }

        order_mixing(event) {
            console.log("mix");

            var id = $(event.currentTarget).attr('id');

            var priceRegex = /[(0-9)]+/igm;
            var menuid = id.match(priceRegex)[0];

            this.isMixing = menuid;
            this.$('#panel-' + menuid).panel("close");
        }

        menu_item(event) {
            console.log("open");

            if(this.isMixing !== null)
            {
                var mixing_menuid = this.isMixing;
                var href = $(event.currentTarget).attr('href');

                var priceRegex = /[(0-9)]+/igm;
                var menuid = href.match(priceRegex)[0];

                var menuSearch = _.find(app.productList.searchHelper, function(obj) { return obj.menuid == menuid; });

                var canMix = app.productList.findWhere({menu_typeid: menuSearch.menu_typeid})
                                            .get("allowMixing");

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

                var currentMixing = this.$('#panel-mixing-text-' + mixing_menuid).html();

                if($.trim(currentMixing) == '')
                    currentMixing = '<b>Gemixt mit:</b>';

                currentMixing += '<div data-menuid="' + menuid + '">' + menuSearch.menu.get('name') + '</div>';

                this.$('#panel-mixing-text-' + mixing_menuid).html(currentMixing);

                this.$('#panel-' + mixing_menuid).panel("open");
            }
        }

        order_count_up(event) {
            console.log("Up");

            var menu_typeid = $(event.currentTarget).attr('data-menu-typeid');
            var index = $(event.currentTarget).attr('data-index');

            var current_amount = this.order.findWhere({menu_typeid: menu_typeid})
                                            .get('orders')
                                            .at(index)
                                            .get('amount');

            current_amount++;

            this.order.findWhere({menu_typeid: menu_typeid})
                        .get('orders')
                        .at(index)
                        .set('amount', current_amount);

            this.renderOrder();
        }

        order_count_down(event) {
            console.log("Down");

            var menu_typeid = $(event.currentTarget).attr('data-menu-typeid');
            var index = $(event.currentTarget).attr('data-index');

            var current_amount = this.order.findWhere({menu_typeid: menu_typeid})
                                            .get('orders')
                                            .at(index)
                                            .get('amount');

            current_amount--;

            if(current_amount < 0)
                    current_amount = 0;

            this.order.findWhere({menu_typeid: menu_typeid})
                        .get('orders')
                        .at(index)
                        .set('amount', current_amount);

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

            var self = this;

            var webservice = new Webservice();

            if(this.mode == 'new')
                webservice.action = "Orders/AddOrder";
            else
                webservice.action = "Orders/ModifyOrder";

            webservice.formData = {order: JSON.stringify(this.order.toJSON()),
                                   options: this.options};
            webservice.callback = {
                success: function(result) {
                    var hasSpecialOrders = false;

                    self.order.each(function(category) {
                        if(!hasSpecialOrders)
                        {
                            var isSpecialOrder = category.get('menu_typeid') == "0";
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
            var itemTemplate = _.template(TemplateItem);

            this.$('#selected').empty();

            var counter = 0;
            var totalSumPrice = 0;

            this.order.each((category) => {
                this.$('#selected').append("<li data-role='list-divider'>" + category.get('name') + "</li>");
                counter = 0;
                var isSpecialOrder = category.get('menu_typeid') == "0";

                category.get('orders').each((originalMenu) => {
                    var menuSearch = _.find(app.productList.searchHelper, function(obj) { return obj.menuid == originalMenu.get('menuid'); });
                    var extras = '';
                    var price = parseFloat(originalMenu.get('price'));
                    var priceFromDB = originalMenu.get('backendID') > 0;

                    var sizeToMixWith = originalMenu.get('sizes').at(0);

                    // Add size text if multible sizes are avaible for the product
                    if(!isSpecialOrder && menuSearch.menu.get('sizes').length > 1)
                    {
                        extras = sizeToMixWith.get('name') + ", ";
                    }
                    if(!priceFromDB) price += parseFloat(sizeToMixWith.get('price'));

                    if(originalMenu.get('mixing').length > 0)
                    {
                        extras += "Gemischt mit: ";

                        originalMenu.get('mixing').each((menuToMixWith) => {
                            extras += menuToMixWith.get('name') + " - ";

                            var menuToMixWithSearch = _.find(app.productList.searchHelper, function(obj) { return obj.menuid == menuToMixWith.get('menuid'); });

                            var sizesOfMenuToMixWith = app.productList.findWhere({menu_typeid: menuToMixWithSearch.menu_typeid})
                                                                        .get('groupes')
                                                                        .findWhere({menu_groupid: menuToMixWithSearch.menu_groupid})
                                                                        .get('menues')
                                                                        .findWhere({menuid: menuToMixWithSearch.menuid})
                                                                        .get('sizes');

                            // -- Price calculation --
                            // First: try to find the same size for the mixing product and get this price
                            var menuToMixWithHasSameSizeAsOriginal = sizesOfMenuToMixWith.findWhere({menu_sizeid: sizeToMixWith.get('menu_sizeid')});
                            var menuToMixWithDefaultPrice = parseFloat(menuToMixWithSearch.menu.get('price'));

                            if(menuToMixWithHasSameSizeAsOriginal)
                            {
                                var priceToAdd = menuToMixWithDefaultPrice + parseFloat(menuToMixWithHasSameSizeAsOriginal.get('price'));

                                if(DEBUG) console.log("Mixing same size found: " + priceToAdd);

                                if(!priceFromDB) price += priceToAdd;
                                return;
                            }

                            // Second: Try to calculate the price based on factor value
                            var menuToMixWithSize = menuToMixWithSearch.menu.get('sizes').at(0);

                            var factor = sizeToMixWith.get('factor') / menuToMixWithSize.get('factor');

                            var priceToAdd = (menuToMixWithDefaultPrice + parseFloat(menuToMixWithSize.get('price')) ) * factor;

                            if(DEBUG) console.log("Mixing factor calculation: " + priceToAdd + " - " + priceToAdd.toFixed(1));

                            if(!priceFromDB) price += priceToAdd;
                            // -- End Price calculation --
                        });

                        if(!priceFromDB)
                        {
                            price = parseFloat( ( price / (originalMenu.get('mixing').length + 1) ) );
                            price = Math.round(price * 10)/10;// avoid cents
                        }

                        extras = extras.slice(0, -3);
                        extras += ", ";
                    }

                    originalMenu.get('extras').each(function(extra){
                        extras += extra.get('name') + ", ";
                        if(!priceFromDB) price += parseFloat(extra.get('price'));
                    });

                    if(originalMenu.get('extra') && originalMenu.get('extra').length > 0)
                        extras += originalMenu.get('extra') + ", ";

                    if(extras.length > 0)
                        extras = extras.slice(0, -2);

                    var totalPrice = price * originalMenu.get('amount');
                    totalSumPrice += totalPrice;

                    var datas = {name: originalMenu.get('name'),
                                extras: extras,
                                mode: 'modify',
                                amount: originalMenu.get('amount'),
                                price: price,
                                totalPrice: totalPrice,
                                menu_typeid: category.get('menu_typeid'),
                                index: counter,
                                isSpecialOrder: isSpecialOrder,
                                skipCounts: false};

                    this.$('#selected').append("<li>" + itemTemplate(datas) + "</li>");
                    counter++;
                });
            });

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
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);
            
            this.renderTemplate(Template, { header: header.render(),
                                            mode: this.mode,
                                            order: this.order,
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