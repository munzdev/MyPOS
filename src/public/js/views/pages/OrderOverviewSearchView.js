// Login View
// =============

// Includes file dependencies
define([ 'views/headers/HeaderView',
         'text!templates/pages/order-overview-search.phtml',
         'jquerymobile-datebox'],
 function(  HeaderView,
            Template ) {
    "use strict";

    // Extends Backbone.View
    var OrderOverviewSearchView = Backbone.View.extend( {

    	title: 'order-overview-search',
    	el: 'body',

        // The View Constructor
        initialize: function() {
            this.searchStatus = 'all';

            this.render();
        },

        events: {
            'click #order-overview-search-footer-back': 'click_btn_back',
            'click #order-overview-search-status a': 'click_btn_status',
            'click #order-overview-search-footer-search': 'click_btn_search'
        },

        click_btn_back: function(event)
        {
            event.preventDefault();
            window.history.back();
        },

        click_btn_search: function()
        {
            var orderid = $.trim($('#order-overview-search-orderid').val());
            var tableNr = $.trim($('#order-overview-search-tableNr').val());
            var from = $.trim($('#order-overview-search-from').val());
            var to = $.trim($('#order-overview-search-to').val());
            var userid = $.trim($('#order-overview-search-userid').val());

            // "order-overview/status/:status(/orderid/:orderid)(/tableNr/:tableNr)(/from/:from)(/to/:to)(/userid/:userid)": "order_overview",
            var searchString = '/status/' + this.searchStatus;

            if(orderid !== '')
            {
                searchString += '/orderid/' + orderid;
            }

            if(tableNr !== '')
            {
                searchString += '/tableNr/' + tableNr;
            }

            if(from !== '')
            {
                searchString += '/from/' + from;
            }

            if(to !== '')
            {
                searchString += '/to/' + to;
            }

            if(userid !== '*')
            {
                searchString += '/userid/' + userid;
            }

            MyPOS.ChangePage('order-overview' + searchString);
        },

        click_btn_status: function(event)
        {
            $('#order-overview-search-status a').removeClass('ui-btn-active');
            $(event.currentTarget).addClass('ui-btn-active');
            this.searchStatus = $(event.currentTarget).attr('data-value');
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();

            header.activeButton = 'order-overview';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  userList: app.session.userList});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }
    } );

    // Returns the View class
    return OrderOverviewSearchView;

} );