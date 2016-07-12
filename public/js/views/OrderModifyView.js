/* global _, Backbone, parseFloat */

// Login View
// =============

// Includes file dependencies
define([ "app",
         "MyPOS",
         "Webservice",
         "collections/OrderCollection",
         "models/order/TypeModel",
         "models/order/MenuModel",
         "models/order/MixingModel",
         "models/order/SizeModel",
         'views/headers/HeaderView',
         'text!templates/pages/order-modify.phtml',
         'text!templates/pages/order-item.phtml'],
function(  app,
            MyPOS,
            Webservice,
            OrderCollection,
            TypeModel,
            MenuModel,
            MixingModel,
            SizeModel,
            HeaderView,
            Template,
            TemplateItem) {
    "use strict";

    // Extends Backbone.View
    var OrderModifyView = Backbone.View.extend( {

    	title: 'order-modify',
    	el: 'body',

    	events: {
            'panelbeforeclose .order-modify-panel': 'order_close',
            'panelbeforeclose #order-modify-panel-special': 'order_special_close',

            // Manually handle events. Ensures that the close event is called after the task
            'click .order-modify-add': 'order_add',
            'click .order-modify-mixing': 'order_mixing',
            'click .order-modify-menu-item': 'menu_item',
            'click #order-modify-panel-special-add': 'order_special_add',
            'click #order-modify-footer-back': 'footer_back',
            'click #order-modify-footer-finish': 'footer_finish',
            'click #order-modify-finished': 'finished'
    	},

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "renderOrder",
                            "order_count_up",
                            "order_count_down",
                            "menu_item",
                            "order_mixing",
                            "order_close",
                            "order_add",
                            "order_special_close",
                            "order_special_add");

            var self = this;

            this.options = options;
            this.isMixing = null;

            this.order = new OrderCollection();

            // Broken Tabs widget with Backboen pushstate enabled  - manual fix it
            $(document).on('pagecreate', '#' + this.title, function(createEvent) {
                self.hideTabs();

                $("#order-modify-tabs a[data-role='tab']").click(function(event) {
                    self.hideTabs();
                    $($(event.currentTarget).attr('href')).show();
                });
            });

            if(options.id === 'new')
            {
                this.mode = 'new';
                this.order.id = 0;
                this.order.tableNr = options.tableNr;
            }
            else
            {
                this.mode = 'edit';
                this.order.id = options.id;
                this.order.fetch({
                    data: {id: options.id},
                    success: this.renderOrder
                });
            }

            this.render();
        },

        hideTabs: function()
        {
            app.session.products.each(function(category){
                $('#order-modify-' + category.get('menu_typeid')).hide();
            });
            $('#order-modify-overview').hide();
        },

        order_add: function(event)
        {
            var id = $(event.currentTarget).attr('id');

            var idRegex = /[(0-9)]+/igm;
            var menuid = id.match(idRegex)[0];

            console.log("add " + menuid);

            var menuSearch = _.find(app.session.products.searchHelper, function(obj) { return obj.menuid === menuid; });
            var menu_typeid = menuSearch.menu_typeid;

            var group = this.order.findWhere({menu_typeid: menu_typeid});

            if(!group)
            {
                group = new TypeModel({menu_typeid: menu_typeid,
                                       name: menuSearch.name});

                this.order.add(group);
            }

            var menu = new MenuModel(menuSearch.menu.attributes);

            var extras = menu.get('extras').clone();
            extras.reset();

            var extrasSelected = $('input[name=extra-' + menuid + ']:checked', '#' + this.title);

            extrasSelected.each(function(index, extra){

                var extraModel = app.session.products.findWhere({menu_typeid: menuSearch.menu_typeid})
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

                var sizeSelected = $('input[name=size-' + menuid + ']:checked', '#' + this.title);

                if(sizeSelected.length === 0)
                {
                    alert('Bitte eine größe Auswählen!');
                    return;
                }

                var sizeModel = app.session.products.findWhere({menu_typeid: menuSearch.menu_typeid})
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
            menu.set('extra', $('#extras-text-' + menuid).val());

            $('#order-modify-panel-mixing-text-' + menuid + ' div').each(function(index, mixing){
                var mixingReference = _.find(app.session.products.searchHelper, function(obj) { return obj.menuid == $(mixing).attr('data-menuid'); });

                var mixingModel = new MixingModel(mixingReference.menu.attributes);

                menu.get('mixing').add(mixingModel);
            });

            group.get('orders').addOnce(menu);

            // Fix jQm Tabs handling as its broken by pushstate (normale simple .tabs(active: last) should work...
            this.hideTabs();
            $('#order-modify-overview').show();
            $('#order-modify-tabs-navbar a.ui-btn-active').removeClass('ui-btn-active');
            $('#order-modify-tabs-navbar-overview').addClass('ui-btn-active');

            this.renderOrder();
            $('#order-modify-panel-' + menuid).panel("close");
        },

        order_close: function(event)
        {
            console.log("close");

            if(this.isMixing !== null)
                return;

            var id = $(event.currentTarget).attr('id');

            var priceRegex = /[(0-9)]+/igm;
            var menuid = id.match(priceRegex)[0];

            $('input[name=size-' + menuid + ']:checked', '#' + this.title).each(function(){
                $(this).attr('checked', false).checkboxradio("refresh");
            });
            $('input[name=extra-' + menuid + ']:checked', '#' + this.title).each(function(){
                $(this).attr('checked', false).checkboxradio("refresh");
            });
            $('#extras-text-' + menuid, '#' + this.title).val("");
            $('#order-modify-panel-mixing-text-' + menuid).html('');
        },

        order_special_add: function(event)
        {
            console.log("add special");

            var specialOrderText = $('#order-modify-panel-special-extra').val();
            specialOrderText = $.trim(specialOrderText);

            if(specialOrderText == '')
            {
                alert('Bitte einen Sonderwunsch eingeben!');
                return;
            }

            var group = this.order.findWhere({menu_typeid: "0"});

            if(!group)
            {
                group = new TypeModel({menu_typeid: "0",
                                       name: "Sonderwünsche"});

                this.order.add(group);
            }

            var menu = new MenuModel();
            menu.set('name', 'Sonderwunsch');
            menu.set('amount', 1);
            menu.set('extra', specialOrderText);

            menu.get('sizes').add(new SizeModel);

            group.get('orders').addOnce(menu);

            this.renderOrder();
            $('#order-modify-panel-special').panel("close");
        },

        order_special_close: function(event)
        {
            console.log("close special");

            $('#order-modify-panel-special-extra').val("");
        },

        order_mixing: function(event)
        {
            console.log("mix");

            var id = $(event.currentTarget).attr('id');

            var priceRegex = /[(0-9)]+/igm;
            var menuid = id.match(priceRegex)[0];

            this.isMixing = menuid;
            $('#order-modify-panel-' + menuid).panel("close");
        },

        menu_item: function(event)
        {
            console.log("open");

            if(this.isMixing !== null)
            {
                var mixing_menuid = this.isMixing;
                var href = $(event.currentTarget).attr('href');

                var priceRegex = /[(0-9)]+/igm;
                var menuid = href.match(priceRegex)[0];

                var menuSearch = _.find(app.session.products.searchHelper, function(obj) { return obj.menuid == menuid; });

                var canMix = app.session.products.findWhere({menu_typeid: menuSearch.menu_typeid})
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

                var currentMixing = $('#order-modify-panel-mixing-text-' + mixing_menuid).html();

                if($.trim(currentMixing) == '')
                    currentMixing = '<b>Gemixt mit:</b>';

                currentMixing += '<div data-menuid="' + menuid + '">' + menuSearch.menu.get('name') + '</div>';

                $('#order-modify-panel-mixing-text-' + mixing_menuid).html(currentMixing);

                $('#order-modify-panel-' + mixing_menuid).panel("open");
            }
        },

        order_count_up: function(event)
        {
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
        },

        order_count_down: function(event)
        {
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
        },

        footer_back: function(event)
        {
            console.log("back");

            event.preventDefault();
            window.history.back();
        },

        footer_finish: function(event)
        {
            console.log("order verify");

            $('#order-modify-verify-dialog').popup('open');
        },

        finished: function(event)
        {
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
                    if(self.mode == 'new')
                        MyPOS.ChangePage("#order-pay/id/" + result.id + "/tableNr/" + self.options.tableNr);
                    else
                        MyPOS.ChangePage("#order-info/id/" + result.id);
                }
            };
            webservice.call();
        },

        renderOrder: function()
        {
            var itemTemplate = _.template(TemplateItem);
            var self = this;

            $('#order-modify-selected').empty();

            var counter = 0;
            var totalSumPrice = 0;

            this.order.each(function(category){
                $('#order-modify-selected').append("<li data-role='list-divider'>" + category.get('name') + "</li>");
                counter = 0;
                var isSpecialOrder = category.get('menu_typeid') == "0";

                category.get('orders').each(function(originalMenu){
                    var menuSearch = _.find(app.session.products.searchHelper, function(obj) { return obj.menuid == originalMenu.get('menuid'); });
                    var extras = '';
                    var price = parseFloat(originalMenu.get('price'));

                    var sizeToMixWith = originalMenu.get('sizes').at(0);

                    // Add size text if multible sizes are avaible for the product
                    if(!isSpecialOrder && menuSearch.menu.get('sizes').length > 1)
                    {
                        extras = sizeToMixWith.get('name') + ", ";
                    }
                    price += parseFloat(sizeToMixWith.get('price'));

                    if(originalMenu.get('mixing').length > 0)
                    {
                        extras += "Gemischt mit: ";

                        originalMenu.get('mixing').each(function(menuToMixWith){
                            extras += menuToMixWith.get('name') + " - ";

                            var menuToMixWithSearch = _.find(app.session.products.searchHelper, function(obj) { return obj.menuid == menuToMixWith.get('menuid'); });

                            var sizesOfMenuToMixWith = app.session.products.findWhere({menu_typeid: menuToMixWithSearch.menu_typeid})
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

                                price += priceToAdd;
                                return;
                            }

                            // Second: Try to calculate the price based on factor value
                            var menuToMixWithSize = menuToMixWithSearch.menu.get('sizes').at(0);

                            var factor = sizeToMixWith.get('factor') / menuToMixWithSize.get('factor');

                            var priceToAdd = (menuToMixWithDefaultPrice + parseFloat(menuToMixWithSize.get('price')) ) * factor;

                            if(DEBUG) console.log("Mixing factor calculation: " + priceToAdd + " - " + priceToAdd.toFixed(1));

                            price += priceToAdd;
                            // -- End Price calculation --
                        });

                        price = parseFloat( ( price / (originalMenu.get('mixing').length + 1) ).toFixed(1) );  // avoid cents

                        extras = extras.slice(0, -3);
                        extras += ", ";
                    }

                    originalMenu.get('extras').each(function(extra){
                        extras += extra.get('name') + ", ";
                        price += parseFloat(extra.get('price'));
                    });

                    if(originalMenu.get('extra').length > 0)
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
                                isSpecialOrder: isSpecialOrder};

                    $('#order-modify-selected').append("<li>" + itemTemplate(datas) + "</li>");
                    counter++;
                });
            });

            $('#order-modify-total').text(parseFloat(totalSumPrice).toFixed(2) + ' €');

            $('.order-item-up').click(this.order_count_up);
            $('.order-item-down').click(this.order_count_down);
            $('#order-modify-selected').listview('refresh');
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();

            if(this.mode == 'new')
                header.activeButton = 'order-new';
            else
                header.activeButton = 'order-overview';


            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  mode: this.mode,
                                                                  order: this.order,
                                                                  products: app.session.products});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }
    });

    return OrderModifyView;
} );