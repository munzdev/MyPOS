// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'collections/manager/CheckCollection',
         'views/headers/HeaderView',
         'views/footers/ManagerFooterView',
         'text!templates/pages/manager-check.phtml',
         'jquery-dateFormat'],
function( Webservice,
          CheckCollection,
          HeaderView,
          ManagerFooterView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var ManagerCheckView = Backbone.View.extend( {

    	title: 'manager-check',
    	el: 'body',
        events: {
            'click #manager-check-verified-status .manager-check-verify-status-btn': 'click_btn_status',
            'click .manager-check-info-btn' : 'click_btn_info',
            'click .manager-check-verify-btn' : 'click_btn_verify',
            'click #manager-check-verify-finished-btn': 'click_btn_verify_finished'
        },

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render");

            if(options.verified == null)
                this.verifiedStatus = '0';
            else
                this.verifiedStatus = options.verified;

            this.checkCollection = new CheckCollection();

            this.fetchList();
        },

        click_btn_status: function(event)
        {
            var verified = $(event.currentTarget).attr('data-value');

            MyPOS.ChangePage('#' + this.title + '/verified/' + $(event.currentTarget).attr('data-value'));
        },

        click_btn_info: function(event)
        {
            var id = $(event.currentTarget).attr('data-id');

            var info = this.checkCollection.findWhere({orders_details_special_extraid: id});

            $('#manager-check-info-tablenr').text(info.get('nameTable'));
            $('#manager-check-info-orderid').text(info.get('orderid'));
            $('#manager-check-info-nameUser').text(info.get('nameUser'));
            $('#manager-check-info-menu-group').text(info.get('nameGroup'));
            $('#manager-check-info-amount').text(info.get('amount'));

            if(info.get('single_price'))
                $('#manager-check-info-single-price').text(parseFloat(info.get('single_price')).toFixed(2) + ' €');
            else
                $('#manager-check-info-single-price').text("");

            $('#manager-check-info-single-price-modified-by').text(info.get('single_price_modified_by'));
            $('#manager-check-info-extra-detail').text(info.get('extra_detail'));
            $('#manager-check-info-verified').text(info.get('verified') == 1 ? 'Ja' : 'Nein');

            var finished = '';

            if(info.get('finished') != '')
                finished = $.format.date(MyPOS.DateFromMysql(info.get('finished')), DATE_JS_TIMEFORMAT);

            $('#manager-check-info-finished').text(finished);

            var availability = '';

            switch(info.get('availability'))
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

            $('#manager-check-info-availability').text(availability);
            $('#manager-check-info-availability-amount').text(info.get('availability_amount'));
            $('#manager-check-info-dialog').popup('open');
        },

        click_btn_verify: function(event)
        {
            var id = $(event.currentTarget).attr('data-id');
            var info = this.checkCollection.findWhere({orders_details_special_extraid: id});

            $('#manager-check-verify-tablenr').text(info.get('nameTable'));
            $('#manager-check-verify-orderid').text(info.get('orderid'));
            $('#manager-check-verify-single-price').val(info.get('single_price'));
            $('#manager-check-verify-menu-group').val(info.get('menu_groupid')).selectmenu('refresh');
            $('#manager-check-verify-availability').val(info.get('availability')).selectmenu('refresh');
            $('#manager-check-verify-availability-amount').val(info.get('availability_amount'));
            $('#manager-check-verify-finished-btn').attr('data-id', id);

            $('#manager-check-verify-dialog').popup('open');
        },

        click_btn_verify_finished: function(event)
        {
            var id = $(event.currentTarget).attr('data-id');
            var info = this.checkCollection.findWhere({orders_details_special_extraid: id});

            var single_price = $('#manager-check-verify-single-price').val();
            var menu_groupid = $('#manager-check-verify-menu-group option:selected').val();
            var availability = $('#manager-check-verify-availability option:selected').val();
            var availability_amount = $('#manager-check-verify-availability-amount').val();

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

                    MyPOS.ReloadPage();
                }
            };
            webservice.call();
        },

        fetchList: function()
        {
            this.checkCollection.fetch({data: {verified: this.verifiedStatus},
                                        success: this.render});
        },

        apiCommandReciever: function(command)
        {
            if(command == 'manager-check')
            {
                MyPOS.ReloadPage();
            }
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();
            var footer = new ManagerFooterView();

            header.activeButton = 'manager';
            footer.activeButton = 'check';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  footer: footer.render(),
                                                                  verifiedStatus: this.verifiedStatus,
                                                                  checks: this.checkCollection,
                                                                  products: app.session.products});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return ManagerCheckView;

} );