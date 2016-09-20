// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'views/footers/AdminFooterView',
         'text!templates/pages/admin/admin-event-modify-printer-modify.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          AdminFooterView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminEventModifyPrinterModifyView = Backbone.View.extend( {

    	title: 'admin-event-modify-printer-modify',
    	el: 'body',
        events: {
            'click #admin-event-modify-printer-modify-save-btn': 'click_save_btn'
        },

        click_save_btn: function()
        {
            var self = this;

            var name = $.trim($('#admin-event-modify-printer-modify-name').val());
            var ip = $.trim($('#admin-event-modify-printer-modify-ip').val());
            var port = $.trim($('#admin-event-modify-printer-modify-port').val());
            var charactersPerRow = $.trim($('#admin-event-modify-printer-modify-characters-per-row').val());

            if(name == '')
            {
                MyPOS.DisplayError('Bitte eine Namen eingebe!');
                return;
            }

            if(ip == '')
            {
                MyPOS.DisplayError('Bitte eine gültige IP eingebe!');
                return;
            }

            if(port == '' || port <= 0)
            {
                MyPOS.DisplayError('Bitte einen gültigen Port eingebe!');
                return;
            }

            if(charactersPerRow == '' || parseInt(charactersPerRow) <= 0)
            {
                MyPOS.DisplayError('Bitte eine gültige Druckergröße eingebe!');
                return;
            }

            if(this.mode == 'new')
            {
                var webservice = new Webservice();
                webservice.action = "Admin/AddEventPrinter";
                webservice.formData = {eventid: this.eventid,
                                       name: name,
                                       ip: ip,
                                       port: port,
                                       characters_per_row: charactersPerRow};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/event/modify/' + self.eventid + "/printer");
                    }
                };
                webservice.call();
            }
            else
            {
                var webservice = new Webservice();
                webservice.action = "Admin/SetEventPrinter";
                webservice.formData = {events_printerid: this.events_printerid,
                                       name: name,
                                       ip: ip,
                                       port: port,
                                       characters_per_row: charactersPerRow};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/event/modify/' + self.eventid + "/printer");
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
            this.ipValue = "";
            this.portValue = "";
            this.charactersPerRowValue = "";

            if(typeof options.events_printerid === 'undefined')
            {
                self.mode = 'new';
                self.events_printerid = 0;
                self.render();
            }
            else
            {
                self.mode = 'edit';
                self.events_printerid = options.events_printerid;

                var webservice = new Webservice();
                webservice.action = "Admin/GetEventPrinter";
                webservice.formData = {events_printerid: self.events_printerid};
                webservice.callback = {
                    success: function(data)
                    {
                        self.nameValue = data.name;
                        self.ipValue = data.ip;
                        self.portValue = data.port;
                        self.charactersPerRowValue = data.characters_per_row;
                        self.render();
                    }
                };
                webservice.call();
            }
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();
            var footer = new AdminFooterView({id: this.eventid});

            header.activeButton = 'event';
            footer.activeButton = 'printer';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  footer: footer.render(),
                                                                  mode: this.mode,
                                                                  name: this.nameValue,
                                                                  ip: this.ipValue,
                                                                  port: this.portValue,
                                                                  charactersPerRow: this.charactersPerRowValue,
                                                                  });

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminEventModifyPrinterModifyView;

} );