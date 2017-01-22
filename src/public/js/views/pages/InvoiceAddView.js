define(['Webservice',
        'models/custom/invoice/InvoiceModel',
        'models/db/Invoice/InvoiceItem',
        'collections/db/Invoice/InvoiceItemCollection',
        'collections/db/Invoice/InvoiceTypeCollection',
        'views/helpers/HeaderView',
        'views/helpers/CustomerSelectView',
        'text!templates/pages/invoice-add.phtml',
        'text!templates/pages/invoice-add-item.phtml'
], function(Webservice,
            InvoiceModel,
            InvoiceItem,
            InvoiceItemCollection,
            InvoiceTypeCollection,
            HeaderView,
            CustomerSelectView,
            Template,
            TemplateItem) {
    "use strict";

    return class InvoiceAddView extends app.PageView
    {
        initialize() {
            this.invoice = new InvoiceModel();
            this.invoice.set('InvoiceItems', new InvoiceItemCollection());

            this.customerSelectView = new CustomerSelectView({selectCallback: this.click_btn_select_customer});
            this.invoiceTypeCollection = new InvoiceTypeCollection();

            let webserviceMaturityDate = new Webservice('Utility/MaturityDate');

            $.mobile.loading("show");
            $.when(webserviceMaturityDate.call(),
                   this.invoiceTypeCollection.fetch())
                .then((result) => {
                    $.mobile.loading("hide");
                    this.render();
                    this.$('#maturityDate').datebox('setTheDate', new Date(result[0].MaturityDate));
                });
        }

        events() {
            return {'click #use-customer': 'click_btn_use_customer',
                    'click #add-entry': 'click_btn_add_entry',
                    'click #saveItem': 'click_btn_saveItem',
                    'click #back': 'click_btn_back',
                    'click #save': 'click_btn_save',
                    'click #finished': 'click_btn_finished',
                    'popupafterclose #form-dialog': 'popup_close'}
        }

        click_btn_use_customer() {
            this.customerSelectView.show();
        }

        click_btn_select_customer(customer) {
            this.$('#customerid').val(customer.get('EventContactid'));
        }

        click_btn_add_entry() {
            this.editCid = false;
            this.$('#form-dialog').popup('open');
        }

        popup_close() {
            this.$('#description').val('');
            this.$('#price').val('');
            this.$('#amount').val('');
            this.$('#tax').val('');
        }

        click_btn_saveItem() {
            if(this.$('#add-form').valid()) {
                if(this.editCid) {
                    var invoiceItem = this.invoice.get('InvoiceItems').get({cid: this.editCid});
                } else {
                    var invoiceItem = new InvoiceItem();
                }
                invoiceItem.set('Description', this.$('#description').val());
                invoiceItem.set('Price', this.$('#price').val());
                invoiceItem.set('Amount', this.$('#amount').val());
                invoiceItem.set('Tax', this.$('#tax').val());

                if(!this.editCid)
                    this.invoice.get('InvoiceItems').add(invoiceItem);

                this.$('#form-dialog').popup('close');

                this.renderItems();
            }
        }

        click_btn_back() {
            window.history.back();
        }

        click_btn_save() {
            this.$('#verify-dialog').popup('open');
        }

        click_btn_finished() {
            if(this.$('#customerid').val())
                this.invoice.set('CustomerEventContactid', this.$('#customerid').val());

            this.invoice.set('MaturityDate', this.$('#maturityDate').datebox('getTheDate').toISOString());
            this.invoice.set('InvoiceTypeid', this.$('#typeid').val());
            this.invoice.save()
                        .done(() => {
                            this.changeHash("invoice");
                        });
        }

        renderItems() {
            let template = _.template(TemplateItem);
            let i18n = this.i18n();
            let total = 0;

            this.$('#invoice-item-list').empty();
            this.invoice.get('InvoiceItems').each((invoiceItem) => {
                total += invoiceItem.get('Price') * invoiceItem.get('Amount');
                this.$('#invoice-item-list').append(template({invoiceItem: invoiceItem,
                                                              t: i18n,
                                                              i18n: app.i18n.template}));
            });

            this.$('.edit-btn').click((event) => {
                let itemCid = $(event.currentTarget).attr('data-item-cid');

                let invoiceItem = this.invoice.get('InvoiceItems').get({cid: itemCid});

                this.$('#description').val(invoiceItem.get('Description'));
                this.$('#price').val(invoiceItem.get('Price'));
                this.$('#amount').val(invoiceItem.get('Amount'));
                this.$('#tax').val(invoiceItem.get('Tax'));

                this.editCid = itemCid;
                this.$('#form-dialog').popup('open');
            });

            this.$('.remove-btn').click((event) => {
                let itemCid = $(event.currentTarget).attr('data-item-cid');

                let invoiceItem = this.invoice.get('InvoiceItems').get({cid: itemCid});

                this.invoice.get('InvoiceItems').remove(invoiceItem);
                this.renderItems();
            });

            this.$('#total').text(app.i18n.toCurrency(total));
        }

        // Renders all of the Category models on the UI
        render() {
            let t = this.i18n();

            let header = new HeaderView();
            this.registerSubview(".nav-header", header);
            this.registerAppendview(this.customerSelectView);

            this.renderTemplate(Template, {invoiceTypeList: this.invoiceTypeCollection});

            // Register new customer form validation
            this.$('#add-form').validate({
                rules: {
                    description: {required: true},
                    amount: {required: true},
                    price: {required: true},
                    tax: {required: true, min: 0}
                },
                messages: {
                    description: {required: t.errorDescription},
                    amount: {required: t.errorAmount},
                    price: {required: t.errorPrice},
                    tax: {required: t.errorTax}
                },
                errorPlacement: function (error, element) {
                    if(element.is('select'))
                        error.appendTo(element.parent().parent().prev());
                    else
                        error.appendTo(element.parent().prev());
                }
            });

            this.changePage(this);

            return this;
        }
    }
} );