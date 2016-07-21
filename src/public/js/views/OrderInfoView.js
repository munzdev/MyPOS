// Login View
// =============

// Includes file dependencies
define([ "app",
         "MyPOS",
         "models/order/info/InfoModel",
         'views/headers/HeaderView',
         'text!templates/pages/order-info.phtml',
         'text!templates/pages/order-item.phtml'],
 function(  app,
            MyPOS,
            InfoModel,
            HeaderView,
            Template,
            TemplateItem) {
    "use strict";

    // Extends Backbone.View
    var OrderInfoView = Backbone.View.extend( {

    	title: 'order-info',
    	el: 'body',

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render",
                            "renderOrder");

            this.infoModel = new InfoModel();

            var self = this;

            this.infoModel.fetch({
                    data: {orderid: options.id},
                    success: function() {
                        self.render();
                    }
                });
        },

        events: {

        },

        renderOrder: function()
        {
            var itemTemplate = _.template(TemplateItem);
            var self = this;

            $('#order-info-details').empty();

            var counter = 0;
            var totalSumPrice = 0;

            this.infoModel.get('orders').each(function(category){
                $('#order-info-details').append("<li data-role='list-divider'>" + category.get('name') + "</li>");
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

                        price = parseFloat( ( price / (originalMenu.get('mixing').length + 1) ) );
                        price = Math.round(price * 10)/10;// avoid cents

                        extras = extras.slice(0, -3);
                        extras += ", ";
                    }

                    originalMenu.get('extras').each(function(extra){
                        extras += extra.get('name') + ", ";
                        price += parseFloat(extra.get('price'));
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
                                skipCounts: true};

                    $('#order-info-details').append("<li>" + itemTemplate(datas) + "</li>");
                    counter++;
                });
            });

            $('#order-info-total').text(parseFloat(totalSumPrice).toFixed(2) + ' €');

            //$('#order-info-details').listview('refresh');
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();

            header.activeButton = 'order-overview';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  order: this.infoModel});

            this.renderOrder();

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }
    } );

    // Returns the View class
    return OrderInfoView;

} );