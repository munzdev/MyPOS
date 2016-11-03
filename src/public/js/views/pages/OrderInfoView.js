// Login View
// =============

// Includes file dependencies
define([ "models/order/info/InfoModel",
         'views/headers/HeaderView',
         'text!templates/pages/order-info.phtml',
         'text!templates/pages/order-item.phtml',
         "jquery-dateFormat"],
 function(  InfoModel,
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

                    if(originalMenu.get('mixing').length > 0)
                    {
                        extras += "Gemischt mit: ";

                        originalMenu.get('mixing').each(function(menuToMixWith){
                            extras += menuToMixWith.get('name') + " - ";
                        });
                        extras = extras.slice(0, -3);
                        extras += ", ";
                    }

                    originalMenu.get('extras').each(function(extra){
                        extras += extra.get('name') + ", ";
                    });

                    if(originalMenu.get('extra') && originalMenu.get('extra').length > 0)
                        extras += originalMenu.get('extra') + ", ";

                    if(extras.length > 0)
                        extras = extras.slice(0, -2);

                    var totalPrice = price * originalMenu.get('amount');
                    totalSumPrice += totalPrice;

                    var status = ORDER_STATUS_WAITING;

                    if(originalMenu.get('in_progress_begin') != null)
                    {
                        status = ORDER_STATUS_IN_PROGRESS;
                    }
                    if(originalMenu.get('in_progress_done') != null)
                    {
                        status = ORDER_STATUS_FINISHED;
                    }

                    var datas = {name: originalMenu.get('name'),
                                extras: extras,
                                mode: 'modify',
                                amount: originalMenu.get('amount'),
                                price: price,
                                totalPrice: totalPrice,
                                menu_typeid: category.get('menu_typeid'),
                                index: counter,
                                isSpecialOrder: isSpecialOrder,
                                skipCounts: true,
                                statusInformation: true,
                                rank: originalMenu.get('rank'),
                                handled_by_name: originalMenu.get('handled_by_name'),
                                in_progress_begin: originalMenu.get('in_progress_begin'),
                                in_progress_done: originalMenu.get('in_progress_done'),
                                amount_recieved_total: originalMenu.get('amount_recieved_total'),
                                amount_recieved: originalMenu.get('amount_recieved'),
                                status: status};

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