define(['views/helpers/HeaderView',
        'text!templates/pages/order-overview-search.phtml',
        'jquerymobile-datebox'
], function(HeaderView,
            Template) {
    "use strict";

    return class OrderOverviewSearchView extends app.PageView {
        initialize() {
            this.searchStatus = 'all';

            this.render();
        }

        events() {
            return {'click #back': 'click_btn_back',
                    'click #status a': 'click_btn_status',
                    'click #search': 'click_btn_search'}
        }

        click_btn_back(event) {
            event.preventDefault();
            this.changeHash('order-overview');
        }

        click_btn_search() {
            var orderid = $.trim(this.$('#orderid').val());
            var tableNr = $.trim(this.$('#tableNr').val());
            var from = $.trim(this.$('#from').val());
            var to = $.trim(this.$('#to').val());
            var userid = $.trim(this.$('#userid').val());

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

            this.changeHash('order-overview' + searchString);
        }

        click_btn_status(event) {
            this.$('#status a').removeClass('ui-btn-active');
            $(event.currentTarget).addClass('ui-btn-active');
            this.searchStatus = $(event.currentTarget).attr('data-value');
        }

        // Renders all of the Category models on the UI
        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template, {userList: app.userList});

            this.changePage(this);

            return this;
        }
    }
} );