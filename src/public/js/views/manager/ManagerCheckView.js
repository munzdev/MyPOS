define(['collections/custom/manager/CheckCollection',
        'views/helpers/HeaderView',
        'views/helpers/ManagerFooterView',
        'text!templates/manager/manager-check.phtml',
        'jquery-dateFormat'
], function(CheckCollection,
            HeaderView,
            ManagerFooterView,
            Template) {
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
            this.checkCollection.verified = this.verifiedStatus;
            this.checkCollection.fetch()
                                .done(() => {
                                    this.render();
                                });
        }

        click_btn_status(event)
        {
            var verified = $(event.currentTarget).attr('data-value');
            this.changeHash(this.id() + '/verified/' + verified);
        }

        click_btn_info(event)
        {
            var cid = $(event.currentTarget).attr('data-cid');
            var orderDetail = this.checkCollection.get({cid: cid});

            this.$('#info-tablenr').text(orderDetail.get('Order').get('EventTable').get('Name'));
            this.$('#info-orderid').text(orderDetail.get('Orderid'));
            this.$('#info-nameUser').text(orderDetail.get('Userid'));
            this.$('#info-amount').text(orderDetail.get('Amount'));

            if (orderDetail.get('MenuGroup'))
                this.$('#info-menu-group').text(orderDetail.get('MenuGroup').get('Name'));
            else
                this.$('#info-menu-group').text('');



            if(orderDetail.get('SinglePrice'))
                this.$('#info-single-price').text(parseFloat(orderDetail.get('SinglePrice')).toFixed(2));
            else
                this.$('#info-single-price').text('');

            this.$('#info-single-price-modified-by').text(orderDetail.get('SinglePriceModifiedByUserid'));
            this.$('#info-extra-detail').text(orderDetail.get('ExtraDetail'));
            this.$('#info-verified').text(orderDetail.get('Verified') == 1 ? 'Ja' : 'Nein');

            var finished = '';

            if(orderDetail.get('DistributionFinished') != null)
                finished = app.i18n.toDateTime(orderDetail.get('DistributionFinished'));

            this.$('#info-finished').text(finished);

            var availability = '';

            switch(orderDetail.get('Availabilityid'))
            {
                case ORDER_AVAILABILITY_AVAILABLE:
                    availability = 'Verfügbar';
                    break;
                case ORDER_AVAILABILITY_DELAYED:
                    availability = 'Verspätet';
                    break;
                case ORDER_AVAILABILITY_OUT_OF_ORDER:
                    availability = 'Ausverkauft';
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
            this.$('#verify-single-price').val(orderDetail.get('SinglePrice'));
            this.$('#verify-menu-group').val(orderDetail.get('MenuGroupid')).selectmenu('refresh');
            this.$('#verify-availability').val(orderDetail.get('Availabilityid')).selectmenu('refresh');
            this.$('#verify-availability-amount').val(orderDetail.get('AvailabilityAmount'));
            this.$('#verify-finished-btn').attr('data-cid', cid);

            this.$('#verify-dialog').popup('open');
        }

        click_btn_verify_finished(event)
        {
            var cid = $(event.currentTarget).attr('data-cid');
            var orderDetail = this.checkCollection.get({cid: cid});

            var single_price = this.$('#verify-single-price').val();
            var menu_groupid = this.$('#verify-menu-group option:selected').val();
            var availability = this.$('#verify-availability option:selected').val();
            var availability_amount = this.$('#verify-availability-amount').val();

            if(single_price == '' || !$.isNumeric(single_price))
            {
                alert("Bitte gültigen Preis eingeben!");
                return;
            }

            if(menu_groupid == undefined || menu_groupid == '')
            {
                alert("Bitte Menügruppe auswählen!");
                return;
            }

            if(availability == undefined || availability == '')
            {
                alert("Bitte Verfügbarkeit auswählen!");
                return;
            }

            if(availability_amount < 0 || Math.round(availability_amount) != availability_amount)
            {
                alert("Bitte gültige Verfügbarkeit Anzahl eingeben!");
                return;
            }

            var webservice = new Webservice();
            webservice.action = "Manager/SetCheckListItem";
            webservice.formData = {orders_details_special_extraid: id,
                                   single_price: single_price,
                                   menu_groupid: menu_groupid,
                                   availability: availability,
                                   availability_amount: availability_amount};
            webservice.callback = {
                success: function() {
                    app.ws.chat.SystemMessage(info.get('userid'), "Ihre aufgenommene Bestellung mit Sonderwunsch vom Tisch " + info.get('nameTable') + " mit der Bestellnummer " + info.get('orderid') + " wurde von " + app.session.user.get('firstname') + " " + app.session.user.get('lastname') + " geprüft und kann nun kassiert werden.");

                    this.reload();
                }
            };
            webservice.call();
        }

        apiCommandReciever(command)
        {
            if(command == 'manager-check')
            {
                this.reload();
            }
        }

        // Renders all of the Category models on the UI
        render() {
            var header = new HeaderView();
            var footer = new ManagerFooterView();
            this.registerSubview(".nav-header", header);
            this.registerSubview(".manager-footer", footer);

            this.renderTemplate(Template, {verifiedStatus: this.verifiedStatus,
                                           checks: this.checkCollection,
                                           products: app.productList});

            this.changePage(this);

            return this;
        }

    }

} );