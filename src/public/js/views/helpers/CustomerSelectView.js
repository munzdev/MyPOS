define(['collections/custom/invoice/CustomerSearchCollection',
        'models/custom/invoice/CustomerModel',
        'text!templates/helpers/customer-select.phtml',
        'jquery-validate'
], function(CustomerSearchCollection,
            CustomerModel,
            Template) {
    "use strict";

    return class CustomerSelectView extends app.RenderView
    {
        initialize(options) {
            this.customerSearch = new CustomerSearchCollection();

            this.selectCallback = options.selectCallback;
            this.allowAdd = options.allowAdd != undefined ? options.allowAdd : true;
            this.lastSelectedCustomer = null;
        }

        events() {
            return {'click #customer-add': 'use_customer_add',
                    'click #customer-search': "customer_search",
                    'click #customer-save': 'customer_save',
                    'popupafterclose #select-customer-popup': 'select_customer_popup_close'}
        }

        show() {
            this.$('#select-customer-popup').popup("open");
        }

        use_customer_add() {
            this.$('#select-customer-popup').popup("close");
            this.$('#add-customer-popup').popup("open");
        }

        customer_search() {
            let name = $.trim(this.$('#customer-search-name').val());

            if(name == '')
                return;

            $.mobile.loading("show");
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
                                        let cid = $(event.currentTarget).attr('data-customercid');
                                        this.lastSelectedCustomer = this.customerSearch.get({cid: cid});
                                        this.selectCallback(this.lastSelectedCustomer);
                                        this.$('#select-customer-popup').popup("close");
                                    });
                                    this.$('#customer-search-result').listview('refresh');
                                    $.mobile.loading("hide");
                                });
        }

        customer_save() {
            if(this.$('#customer-form').valid()) {
                let customer = new CustomerModel;
                customer.set('Title', this.$('#customer-title').val());
                customer.set('Name', this.$('#customer-name').val());
                customer.set('ContactPerson', this.$('#customer-contact-person').val() == '' ? null : this.$('#customer-contact-person').val());
                customer.set('Address', this.$('#customer-address').val());
                customer.set('Address2', this.$('#customer-address2').val() == '' ? null : this.$('#customer-address2').val());
                customer.set('City', this.$('#customer-city').val());
                customer.set('Zip', this.$('#customer-zip').val());
                customer.set('TaxIdentificationNr', this.$('#customer-tin').val() == '' ? null : this.$('#customer-tin').val());
                customer.set('Telephon', this.$('#customer-telephone').val() == '' ? null : this.$('#customer-telephone').val());
                customer.set('Fax', this.$('#customer-fax').val() == '' ? null : this.$('#customer-fax').val());
                customer.set('Email', this.$('#customer-email').val() == '' ? null : this.$('#customer-email').val());
                customer.save()
                        .done(() => {
                            this.lastSelectedCustomer = customer;
                            this.selectCallback(customer);
                            this.$('#add-customer-popup').popup("close");
                        });
                return false;
            }
        }

        select_customer_popup_close() {
            this.$('#customer-search-name').val('');
            this.$('#customer-search-result').empty();
        }

        // Renders all of the Category models on the UI
        render() {
            let t = this.i18n();
            this.renderTemplate(Template, {allowAdd: this.allowAdd});

            // Register new customer form validation
            this.$('#customer-form').validate({
                rules: {
                    title: {required: true},
                    name: {required: true},
                    address: {required: true},
                    city: {required: true},
                    zip: {required: true}
                },
                messages: {
                    title: {required: t.errorTitle},
                    name: {required: t.errorName},
                    address: {required: t.errorAddress},
                    city: {required: t.errorCity},
                    zip: {required: t.errorZip}
                },
                errorPlacement: function (error, element) {
                    if(element.is('select'))
                        error.appendTo(element.parent().parent().prev());
                    else
                        error.appendTo(element.parent().prev());
                }
            });

            return this;
        }
    }
} );