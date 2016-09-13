// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'views/footers/AdminFooterView',
         'collections/products/ExtraCollection',
         'collections/products/SizeCollection',
         'text!templates/pages/admin/admin-event-modify-menu-modify.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          AdminFooterView,
          ExtraCollection,
          SizeCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminEventModifyMenuModifyView = Backbone.View.extend( {

    	title: 'admin-event-modify-menu-modify',
    	el: 'body',
        events: {
            'click #admin-event-modify-menu-modify-save-btn': 'click_save_btn'
        },

        click_save_btn: function()
        {
            var name = $.trim($('#admin-event-modify-menu-modify-name').val());
            var price = $.trim($('#admin-event-modify-menu-modify-price').val());
            var availability = $.trim($('#admin-event-modify-menu-modify-availability').val());
            var availabilityAmount = $.trim($('#admin-event-modify-menu-modify-availability-amount').val());
            var selectedExtras = $('#admin-event-modify-menu-modify-optons input[name="admin-event-modify-menu-modify-extra"]:checked');
            var selectedSizes = $('#admin-event-modify-menu-modify-optons input[name="admin-event-modify-menu-modify-size"]:checked');
            var extras = {};
            var sizes = {};

            if(name == '')
            {
                MyPOS.DisplayError('Bitte einen Namen eingeben!');
                return;
            }

            if(price == '' || price < 0)
            {
                MyPOS.DisplayError('Bitte einen gültigen Preis eingeben!');
                return;
            }

            if(availabilityAmount == '')
                availabilityAmount = 0;

            if(availabilityAmount < 0)
            {
                MyPOS.DisplayError('Bitte eine gültige Anzahl von Verfügbarkeiten eingeben!');
                return;
            }

            var error = false;

            selectedExtras.each(function() {
                var extra = $(this);
                var extraPrice = $.trim($("#admin-event-modify-menu-modify-extra-price-" + extra.val()).val());

                if(extraPrice == '')
                    extraPrice = 0;

                if(price + extraPrice < 0)
                {
                    error = 'Bitte einen gültigen Extra Preis eingeben! (Preis zu hoch)';
                    return false;
                }

                extras[extra.val()] = extraPrice;
            });

            selectedSizes.each(function() {
                var size = $(this);
                var sizePrice = $.trim($("#admin-event-modify-menu-modify-size-price-" + size.val()).val());

                if(sizePrice == '')
                    sizePrice = 0;

                if(price + sizePrice < 0)
                {
                    error = 'Bitte einen gültigen Größen Preis eingeben! (Preis zu hoch)';
                    return false;
                }

                sizes[size.val()] = sizePrice;
            });

            if(error !== false)
            {
                MyPOS.DisplayError(error);
                return;
            }

            if(_.size(sizes) == 0)
            {
                MyPOS.DisplayError('Bitte mindestens 1 Größe auswählen!');
                return;
            }

            if(this.mode == 'new')
            {
                var webservice = new Webservice();
                webservice.action = "Admin/AddMenu";
                webservice.formData = {eventid: this.eventid,
                                       groupid: this.groupid,
                                       name: name,
                                       price: price,
                                       availability: availability,
                                       availabilityAmount: availabilityAmount,
                                       extras: extras,
                                       sizes: sizes};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/menu/modify/' + this.eventid + "/menu");
                    }
                };
                webservice.call();
            }
            else
            {
                var webservice = new Webservice();
                webservice.action = "Admin/SetMenu";
                webservice.formData = {id: this.menuid,
                                       name: name,
                                       price: price,
                                       availability: availability,
                                       availabilityAmount: availabilityAmount,
                                       extras: extras,
                                       sizes: sizes};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/menu/modify/' + this.eventid + "/menu");
                    }
                };
                webservice.call();
            }
        },

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render",
                            "click_save_btn");

            var self = this;

            this.eventid = options.id;

            this.nameValue = "";
            this.availabilityAmountValue = "";
            this.availabilityValue = "";
            this.availabilityAmountValue = "";
            this.allowMixingValue = 0;

            this.extrasList = new ExtraCollection();
            this.extrasList.url = app.API + "Admin/GetEventExtras/";

            this.sizesList = new SizeCollection();
            this.sizesList.url = app.API + "Admin/GetMenuSizes/";

            $.when(this.extrasList.fetch({data: {eventid: this.eventid}}),
                   this.sizesList.fetch()).then(function() {
                if(typeof options.menuid === 'undefined')
                {
                    self.mode = 'new';
                    self.groupid = options.groupid;
                    self.render();
                }
                else
                {
                    self.mode = 'edit';
                    self.menuid = options.menuid;

                    var webservice = new Webservice();
                    webservice.action = "Admin/GetMenu";
                    webservice.formData = {id: self.menuid};
                    webservice.callback = {
                        success: function(data)
                        {
                            self.nameValue = data.name;
                            self.priceValue = data.price;
                            self.availabilityValue = data.availability;
                            self.availabilityAmountValue = data.availabilityAmount;
                            self.allowMixingValue = data.allowMixing;
                            self.render();
                        }
                    };
                    webservice.call();
                }
            });
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();
            var footer = new AdminFooterView({id: this.eventid});

            header.activeButton = 'event';
            footer.activeButton = 'menu';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  footer: footer.render(),
                                                                  mode: this.mode,
                                                                  name: this.nameValue,
                                                                  price: this.availabilityAmountValue,
                                                                  availability: this.availabilityValue,
                                                                  availabilityAmount: this.availabilityAmountValue,
                                                                  allowMixing: this.allowMixingValue,
                                                                  sizes: this.sizesList,
                                                                  extras: this.extrasList});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminEventModifyMenuModifyView;

} );