define(['collections/custom/manager/CheckCollection',
        'views/helpers/HeaderView',
        'views/helpers/ManagerFooterView',
        'text!templates/manager/manager-check.phtml',
        'text!templates/manager/manager-check-item.phtml',
        'jquery-dateFormat'
], function(CheckCollection,
            HeaderView,
            ManagerFooterView,
            Template,
            TemplateItem) {
    "use strict";

    // TODO: Also check new tables orders here. New tables need to be associated to a distribution place for products
    return class ManagerCheckView extends app.PageView {

    	events() {
            return {'click #verified-status .verify-status-btn': 'click_btn_status',
                    'click .info-btn' : 'click_btn_info',
                    'click .verify-btn' : 'click_btn_verify',
                    'click #verify-finished-btn': 'click_btn_verify_finished'}
        }

        // The View Constructor
        initialize(options) {
            if(options.verified == null)
                this.verifiedStatus = '0';
            else
                this.verifiedStatus = options.verified;

            this.checkCollection = new CheckCollection();

            this.refresh();
        }

        click_btn_status(event)
        {
            var verified = $(event.currentTarget).attr('data-value');
            this.verifiedStatus = verified;
            this.refresh();
        }

        click_btn_info(event)
        {
            var t = this.i18n();
            let i18n = app.i18n.template;
            var cid = $(event.currentTarget).attr('data-cid');
            var orderDetail = this.checkCollection.get({cid: cid});

            this.$('#info-tablenr').text(orderDetail.get('Order').get('EventTable').get('Name'));
            this.$('#info-orderid').text(orderDetail.get('Orderid'));
            this.$('#info-nameUser').text(orderDetail.get('Order').get('User').get('Firstname') + ' ' + orderDetail.get('Order').get('User').get('Lastname'));
            this.$('#info-amount').text(orderDetail.get('Amount'));

            if (orderDetail.get('MenuGroup'))
                this.$('#info-menu-group').text(orderDetail.get('MenuGroup').get('Name'));
            else
                this.$('#info-menu-group').text('');

            if(orderDetail.get('SinglePrice'))
                this.$('#info-single-price').text(parseFloat(orderDetail.get('SinglePrice')).toFixed(2));
            else
                this.$('#info-single-price').text('');

            let singlePriceModifiedBy = '';

            if (orderDetail.get('User')) {
                singlePriceModifiedBy = orderDetail.get('User').get('Firstname') + ' ' + orderDetail.get('User').get('Lastname');
            }

            this.$('#info-single-price-modified-by').text(singlePriceModifiedBy);
            this.$('#info-extra-detail').text(orderDetail.get('ExtraDetail'));
            this.$('#info-verified').text(orderDetail.get('Verified') == 1 ? t.yes : t.no);

            var finished = '';

            if(orderDetail.get('DistributionFinished') != null)
                finished = app.i18n.toDateTime(orderDetail.get('DistributionFinished'));

            this.$('#info-finished').text(finished);

            var availability = '';

            switch(orderDetail.get('Availabilityid'))
            {
                case ORDER_AVAILABILITY_AVAILABLE:
                    availability = i18n.Availability.AVAILABLE;
                    break;
                case ORDER_AVAILABILITY_DELAYED:
                    availability = i18n.Availability.DELAYED;
                    break;
                case ORDER_AVAILABILITY_OUT_OF_ORDER:
                    availability = i18n.Availability.OUT_OF_ORDER;
                    break;
            }

            this.$('#info-availability').text(availability);
            this.$('#info-availability-amount').text(orderDetail.get('AvailabilityAmount'));
            this.$('#info-dialog').popup('open');
        }

        click_btn_verify(event)
        {
            var cid = $(event.currentTarget).attr('data-cid');
            var orderDetail = this.checkCollection.get({cid: cid});

            this.$('#verify-tablenr').text(orderDetail.get('Order').get('EventTable').get('Name'));
            this.$('#verify-orderid').text(orderDetail.get('Orderid'));
            this.$('#verify-extra-detail').text(orderDetail.get('ExtraDetail'));
            this.$('#verify-single-price').val(orderDetail.get('SinglePrice'));
            this.$('#verify-menu-group').val(orderDetail.get('MenuGroupid')).selectmenu('refresh');
            this.$('#verify-availability').val(orderDetail.get('Availabilityid')).selectmenu('refresh');
            this.$('#verify-availability-amount').val(orderDetail.get('AvailabilityAmount'));
            this.$('#verify-finished-btn').attr('data-cid', cid);

            this.$('#verify-dialog').popup('open');
        }

        click_btn_verify_finished(event)
        {
            if(this.$('#edit-form').valid()) {
                var cid = $(event.currentTarget).attr('data-cid');
                var orderDetail = this.checkCollection.get({cid: cid});

                orderDetail.set('SinglePrice', this.$('#verify-single-price').val());
                orderDetail.set('MenuGroupid', this.$('#verify-menu-group option:selected').val());
                orderDetail.set('Availabilityid', this.$('#verify-availability option:selected').val());
                orderDetail.set('AvailabilityAmount', this.$('#verify-availability-amount').val());
                orderDetail.save()
                           .done(() => {
                               let i18n = this.i18n();
                               let systemMessage = sprintf(i18n.systemMessage, {name: app.auth.authUser.get('Firstname') + " " + app.auth.authUser.get('Lastname'),
                                                                                orderid: orderDetail.get('Orderid'),
                                                                                table: orderDetail.get('Order').get('EventTable').get('Name')});

                               app.ws.chat.SystemMessage(orderDetail.get('Order').get('Userid'), systemMessage);
                               this.refresh();
                               this.$('#verify-dialog').popup("close");
                           });
            }
        }

        apiCommandReciever(command)
        {
            if(command == 'manager-check')
            {
                this.reload();
            }
        }

        refresh() {
            if(!this.rendered) {
                this.render();
                this.rendered = true;
            }

            this.$('#check-list').empty();
            $.mobile.loading("show");


            this.checkCollection.fetch({data: {verified: this.verifiedStatus}})
                .done(() => {
                    this.renderCheckList();
                });
        }

        renderCheckList() {
            $.mobile.loading("hide");
            let template = _.template(TemplateItem);
            let i18n = this.i18n();

            this.checkCollection.each((orderItem) => {
                this.$('#check-list').append(template({orderItem: orderItem,
                                                       t: i18n,
                                                       i18n: app.i18n.template}));
            });
        }

        // Renders all of the Category models on the UI
        render() {
            let t = this.i18n();
            let header = new HeaderView();
            let footer = new ManagerFooterView();
            this.registerSubview(".nav-header", header);
            this.registerSubview(".manager-footer", footer);

            this.renderTemplate(Template, {verifiedStatus: this.verifiedStatus,
                                           checks: this.checkCollection,
                                           products: app.productList});

            this.$('#edit-form').validate({
                rules: {
                    price: {required: true,
                            number: true,
                            min: 0},
                    menuGroup: {required: true},
                    availability: {required: true},
                    availabilityAmount: {digits: true,
                                         min: 0},
                },
                messages: {
                    price: t.errorPrice,
                    menuGroup: t.errorMenuGroup,
                    availability: t.errorAvailability,
                    availabilityAmount: t.errorAvailabilityAmount
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