define(['collections/db/Invoice/InvoiceTypeCollection',
        'collections/custom/invoice/CustomerSearchCollection',
        'views/helpers/HeaderView',
        'text!templates/pages/invoice-overview-search.phtml'
], function(InvoiceTypeCollection,
            CustomerSearchCollection,
            HeaderView,
            Template) {
    "use strict";

    return class InvoiceOverviewSearchView extends app.PageView {
        initialize(options) {

            this.search = options;
            this.invoiceTypeCollection = new InvoiceTypeCollection();
            this.customerSearch = new CustomerSearchCollection;

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
                    'click #select-customer': "click_select_customer",
                    'click #customer-search': "click_customer_search",
                    'popupafterclose #select-customer-popup': 'close_select_customer_popup'}
        }

        click_select_customer() {
            this.$('#select-customer-popup').popup("open");
        }

        click_customer_search() {
            let name = $.trim(this.$('#customer-search-name').val());

            if(name == '')
                return;

            this.customerSearch.name = name;
            this.customerSearch.fetch()
                                .done(() => {
                                    this.$('#customer-search-result').empty();
                                    let t = this.i18n();

                                    let divider = $('<li/>').attr('data-role', 'list-divider').text(t.searchResult);
                                    this.$('#customer-search-result').append(divider);

                                    if(this.customerSearch.length == 0) {
                                        this.$('#customer-search-result').append($('<li/>').text(t.noSearchResult));
                                    } else {
                                        this.customerSearch.each((customer) => {
                                            let a = $('<a/>').attr('class', "customer-search-result-btn ui-btn ui-corner-all ui-shadow ui-btn-b ui-mini ui-icon-check ui-btn-icon-right")
                                                             .attr('data-customercid', customer.cid)
                                                             .text(customer.get('Name'));

                                            this.$('#customer-search-result').append($('<li/>').append(a));
                                        });
                                    }

                                    this.$('.customer-search-result-btn').click((event) => {
                                        let customer = this.customerSearch.get({cid: $(event.currentTarget).attr('data-customercid')});
                                        this.$('#customerid').val(customer.get('EventContactid'));
                                        this.$('#select-customer-popup').popup("close");
                                    });
                                    this.$('#customer-search-result').listview('refresh');
                                });
        }

        close_select_customer_popup() {
            this.$('#customer-search-name').val('');
            this.$('#customer-search-result').empty();
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

            this.renderTemplate(Template, {userList: app.userList,
                                           invoiceTypeList: this.invoiceTypeCollection});

            this.changePage(this);

            return this;
        }
    }
} );