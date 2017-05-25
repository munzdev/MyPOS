define(['views/helpers/CustomerSelectView',
        'views/helpers/HeaderView',
        'text!templates/pages/invoice-overview-search.phtml'
], function(CustomerSelectView,
            HeaderView,
            Template) {
    "use strict";

    return class InvoiceOverviewSearchView extends app.PageView {
        initialize(options) {

            this.search = options;
            this.invoiceTypeCollection = new app.collections.Invoice.InvoiceTypeCollection();
            this.customerSelectView = new CustomerSelectView({selectCallback: this.select_customer,
                                                              allowAdd: false});

            $.mobile.loading("show");
            this.invoiceTypeCollection.fetch()
                                      .done(() => {
                                            $.mobile.loading("hide");
                                            this.render();

                                            this.$('#' + options.status).attr("checked", true).checkboxradio("refresh");
                                            this.$('#' + options.canceled).attr("checked", true).checkboxradio("refresh");

                                            if(options.invoiceid)
                                                this.$('#invoiceid').val(options.invoiceid);

                                            if(options.customerid)
                                                this.$('#customerid').val(options.customerid);

                                            if(options.typeid)
                                                this.$('#typeid').val(options.typeid).selectmenu('refresh');;

                                            if(options.from)
                                                this.$('#from').val(options.from);

                                            if(options.to)
                                                this.$('#to').val(options.to);

                                            if(options.userid)
                                                this.$('#userid').val(options.userid).selectmenu('refresh');
                                            else
                                                this.$('#userid').val(app.auth.authUser.get('Userid')).selectmenu('refresh');
                                      });
        }

        events() {
            return {'click #back': 'click_btn_back',
                    'click #search': 'click_btn_search',
                    'click #select-customer': "click_select_customer"}
        }

        click_select_customer() {
            this.customerSelectView.show();
        }

        select_customer(customer) {
            this.$('#customerid').val(customer.get('EventContactid'));
        }

        click_btn_back(event) {
            event.preventDefault();

            this.showResult(this.search.status,
                            this.search.invoiceid,
                            this.search.customerid,
                            this.search.canceled,
                            this.search.typeid,
                            this.search.from,
                            this.search.to,
                            this.search.userid);
        }

        click_btn_search() {
            var status = this.$('#status :radio:checked').val();
            var canceled = this.$('#canceled :radio:checked').val();
            var invoiceid = $.trim(this.$('#invoiceid').val());
            var customerid = $.trim(this.$('#customerid').val());
            var typeid = $.trim(this.$('#typeid').val());
            var from = this.$('#from');
            var to = this.$('#to');
            var userid = this.$('#userid').val();

            if(from.val() == '')
                from = '';
            else
                from = $.format.date(from.datebox('getTheDate'), DATE_JS_DATEFORMAT);

            if(to.val() == '')
                to = '';
            else
                to = $.format.date(to.datebox('getTheDate'), DATE_JS_DATEFORMAT);

            this.showResult(status, invoiceid, customerid, canceled, typeid, from, to, userid);
        }

        showResult(status, invoiceid, customerid, canceled, typeid, from, to, userid) {
            var searchString = '/status/' + status;

            if(invoiceid)
                searchString += '/invoiceid/' + invoiceid;

            if(customerid)
                searchString += '/customerid/' + customerid;

            if(canceled)
                searchString += '/canceled/' + canceled;

            if(typeid)
                searchString += '/typeid/' + typeid;

            if(from)
                searchString += '/from/' + from;

            if(to)
                searchString += '/to/' + to;

            if(userid)
                searchString += '/userid/' + userid;

            this.changeHash('invoice' + searchString);
        }

        // Renders all of the Category models on the UI
        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);
            this.registerAppendview(this.customerSelectView);

            this.renderTemplate(Template, {user: app.user,
                                           invoiceTypeList: this.invoiceTypeCollection});

            this.changePage(this);

            return this;
        }
    }
} );