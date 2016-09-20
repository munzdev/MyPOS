// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'views/footers/AdminFooterView',
         'collections/admin/EventUserCollection',
         'text!templates/pages/admin/admin-event-modify-user.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          AdminFooterView,
          EventUserCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminUserView = Backbone.View.extend( {

    	title: 'admin-event-modify-user',
    	el: 'body',
        events: {
            'click #admin-event-modify-user-add-btn': 'click_add_btn',
            'click .admin-event-modify-user-edit-btn': 'click_edit_btn',
            'click .admin-event-modify-user-delete-btn': 'click_delete_btn',
            'click #admin-event-modify-user-delete-dialog-finished': 'click_delete_finished_btn'
        },

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render");

            this.id = options.id;

            this.userList = new EventUserCollection();
            this.userList.fetch({data: {eventid: this.id},
                                 success: this.render});
        },

        click_add_btn: function()
        {
            MyPOS.ChangePage('#admin/event/modify/' + this.id + '/user/add');
        },

        click_edit_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-events-user-id');

            MyPOS.ChangePage('#admin/event/modify/' + this.id + '/user/modify/' + id);
        },

        click_delete_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-events-user-id');

            this.deleteId = id;

            $('#admin-event-modify-user-delete-dialog').popup('open');
        },

        click_delete_finished_btn: function()
        {
            $('#admin-event-modify-user-delete-dialog').popup('close');

            var webservice = new Webservice();
            webservice.action = "Admin/DeleteEventUser";
            webservice.formData = {events_userid: this.deleteId};
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
            footer.activeButton = 'user';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  footer: footer.render(),
                                                                  users: this.userList});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminUserView;

} );