// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'views/headers/AdminHeaderView',
         'views/footers/AdminFooterView',
         'collections/PrinterCollection',
         'text!templates/pages/admin/event/printer.phtml'],
function( Webservice,
          AdminHeaderView,
          AdminFooterView,
          PrinterCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var PrinterView = Backbone.View.extend( {

    	title: 'admin-event-modify-printer',
    	el: 'body',
        events: {
            'click #admin-event-modify-printer-add-btn': 'click_add_btn',
            'click .admin-event-modify-printer-default-btn': 'click_default_btn',
            'click .admin-event-modify-printer-edit-btn': 'click_edit_btn',
            'click .admin-event-modify-printer-delete-btn': 'click_delete_btn',
            'click #admin-event-modify-printer-delete-dialog-finished': 'click_delete_finished_btn'
        },

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render");

            this.id = options.id;

            this.printerList = new PrinterCollection();
            this.printerList.url = app.API + "Admin/GetEventPrinterList/";
            this.printerList.fetch({data: {eventid: this.id},
                                    success: this.render});
        },

        click_add_btn: function()
        {
            MyPOS.ChangePage('#admin/event/modify/' + this.id + '/printer/add');
        },

        click_default_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-printer-id');

            var webservice = new Webservice();
            webservice.action = "Admin/SetEventPrinterDefault";
            webservice.formData = {events_printerid: id};
            webservice.callback = {
                success: function()
                {
                    MyPOS.ReloadPage();
                }
            };
            webservice.call();
        },

        click_edit_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-printer-id');

            MyPOS.ChangePage('#admin/event/modify/' + this.id + '/printer/modify/' + id);
        },

        click_delete_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-printer-id');

            this.deleteId = id;

            $('#admin-event-modify-printer-delete-dialog').popup('open');
        },

        click_delete_finished_btn: function()
        {
            $('#admin-event-modify-printer-delete-dialog').popup('close');

            var webservice = new Webservice();
            webservice.action = "Admin/DeleteEventPrinter";
            webservice.formData = {events_printerid: this.deleteId};
            webservice.callback = {
                success: function()
                {
                    MyPOS.ReloadPage();
                }
            };
            webservice.call();
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();
            var footer = new AdminFooterView({id: this.id});

            header.activeButton = 'event';
            footer.activeButton = 'printer';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  footer: footer.render(),
                                                                  printers: this.printerList});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminEventModifyPrinterView;

} );