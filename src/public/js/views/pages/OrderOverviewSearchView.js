define(['views/helpers/HeaderView',
        'text!templates/pages/order-overview-search.phtml',
        'jquerymobile-datebox'
], function(HeaderView,
            Template) {
    "use strict";

    return class OrderOverviewSearchView extends app.PageView {
        initialize(options) {

            this.search = options;
            this.render();

            this.$('#' + options.status).attr("checked", true).checkboxradio("refresh");

            if(options.orderid)
                this.$('#orderid').val(options.orderid);

            if(options.tableNr)
                this.$('#tableNr').val(options.tableNr);

            if(options.from)
                this.$('#from').val(options.from);

            if(options.to)
                this.$('#to').val(options.to);

            if(options.userid)
                this.$('#userid').val(options.userid).selectmenu('refresh');
            else
                this.$('#userid').val(app.auth.authUser.get('Userid')).selectmenu('refresh');
        }

        events() {
            return {'click #back': 'click_btn_back',
                    'click #search': 'click_btn_search'}
        }

        click_btn_back(event) {
            event.preventDefault();

            this.showResult(this.search.status,
                            this.search.orderid,
                            this.search.tableNr,
                            this.search.from,
                            this.search.to,
                            this.search.userid);
        }

        click_btn_search() {
            var status = this.$('#status :radio:checked').val();
            var orderid = $.trim(this.$('#orderid').val());
            var tableNr = $.trim(this.$('#tableNr').val());
            var from = this.$('#from').val();
            var to = this.$('#to').val();
            var userid = this.$('#userid').val();

            this.showResult(status, orderid, tableNr, from, to, userid);
        }

        showResult(status, orderid, tableNr, from, to, userid) {
            var searchString = '/status/' + status;

            if(orderid)
            {
                searchString += '/orderid/' + orderid;
            }

            if(tableNr)
            {
                searchString += '/tableNr/' + tableNr;
            }

            if(from)
            {
                searchString += '/from/' + from;
            }

            if(to)
            {
                searchString += '/to/' + to;
            }

            if(userid)
            {
                searchString += '/userid/' + userid;
            }

            this.changeHash('order-overview' + searchString);
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