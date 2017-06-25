define(['views/helpers/CustomerSelectView',
        'text!templates/pages/invoice-overview-search.phtml'
], function(CustomerSelectView,
            Template) {
    "use strict";

    return class InvoiceOverviewSearchView extends app.PageView {
        initialize(options) {
            this.search = options;
            this.invoiceTypeCollection = new app.collections.Invoice.InvoiceTypeCollection();
            this.customerSelectView = new CustomerSelectView({selectCallback: this.select_customer,
                                                              allowAdd: false});

            this.refresh();
        }

        events() {
            return {'click #back': 'click_btn_back',
                    'click #search': 'click_btn_search',
                    'click #select-customer': "click_select_customer"}
        }

        refresh() {
            let i18n = this.i18n();

            if(!this.rendered) {
                this.render();
                this.rendered = true;
            }

            this.$('#typeid').empty();

            this.fetchData(this.invoiceTypeCollection.fetch(), i18n.loading);
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

        onDataFetched() {
            let i18n = this.i18n();
            let optionAll = $('<option/>', {value: ''});
            optionAll.text(i18n.all);

            this.$('#typeid').append(optionAll);

            this.invoiceTypeCollection.each((invoiceType) => {
                let option = $('<option/>', {value: invoiceType.get('InvoiceTypeid')});
                option.text(app.i18n.template.InvoiceType[invoiceType.get('Name')]);

                this.$('#typeid').append(option);
            });

            this.$('#typeid').selectmenu("refresh");
            this.$('#' + this.search.status).attr("checked", true).checkboxradio("refresh");
            this.$('#' + this.search.canceled).attr("checked", true).checkboxradio("refresh");

            if(this.search.invoiceid)
                this.$('#invoiceid').val(this.search.invoiceid);

            if(this.search.customerid)
                this.$('#customerid').val(this.search.customerid);

            if(this.search.typeid)
                this.$('#typeid').val(this.search.typeid).selectmenu('refresh');;

            if(this.search.from)
                this.$('#from').val(this.search.from);

            if(this.search.to)
                this.$('#to').val(this.search.to);

            if(this.search.userid)
                this.$('#userid').val(this.search.userid).selectmenu('refresh');
            else
                this.$('#userid').val(app.auth.authUser.get('Userid')).selectmenu('refresh')
        }

        render() {
            this.registerAppendview(this.customerSelectView);
            this.renderTemplate(Template, {userList: app.userList});

            this.changePage(this);
        }
    }
} );