// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'collections/admin/EventsCollection',
         'text!templates/pages/admin/admin-event.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          EventsCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminEventView = Backbone.View.extend( {

    	title: 'admin-event',
    	el: 'body',
        events: {
            'click .admin-event-add-btn': 'click_add_btn',
            'click .admin-event-copy-btn': 'click_copy_btn',
            'click .admin-event-edit-btn': 'click_edit_btn',
            'click .admin-event-delete-btn': 'click_delete_btn',
            'click .admin-event-active-btn': 'click_active_btn',
            'click #admin-event-delete-dialog-finished': 'click_delete_finished_btn'
        },

        click_add_btn: function()
        {
            MyPOS.ChangePage('admin/event/add');
        },

        click_copy_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-event-id');

            MyPOS.ChangePage('admin/event/copy/id/'+id);
        },

        click_edit_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-event-id');

            MyPOS.ChangePage('admin/event/modify/'+id);
        },

        click_delete_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-event-id');

            this.deleteId = id;

            $('#admin-event-delete-dialog').popup('open');
        },

        click_delete_finished_btn: function()
        {
            $('#admin-event-delete-dialog').popup('close');

            var webservice = new Webservice();
            webservice.action = "Admin/EventDelete";
            webservice.formData = {eventid: this.deleteId};
            webservice.callback = {
                success: function()
                {
                    MyPOS.ReloadPage();
                }
            };
            webservice.call();
        },

        click_active_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-event-id');

            var webservice = new Webservice();
            webservice.action = "Admin/EventSetActive";
            webservice.formData = {eventid: id};
            webservice.callback = {
                success: function()
                {
                    MyPOS.ReloadPage();
                }
            };
            webservice.call();
        },

        // The View Constructor
        initialize: function() {
            _.bindAll(this, "render");

            this.eventsList = new EventsCollection();
            this.eventsList.fetch({success: this.render});
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();

            header.activeButton = 'event';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  events: this.eventsList});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminEventView;

} );