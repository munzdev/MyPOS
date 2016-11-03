// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'views/headers/AdminHeaderView',
         'collections/user/UserCollection',
         'text!templates/pages/admin/admin-user.phtml'],
function( Webservice,
          AdminHeaderView,
          UserCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminUserView = Backbone.View.extend( {

    	title: 'admin-user',
    	el: 'body',
        events: {
            'click #admin-user-add-btn': 'click_add_btn',
            'click .admin-user-edit-btn': 'click_edit_btn',
            'click .admin-user-delete-btn': 'click_delete_btn',
            'click #admin-user-delete-dialog-finished': 'click_delete_finished_btn'
        },

        // The View Constructor
        initialize: function() {
            _.bindAll(this, "render");

            this.userList = new UserCollection();
            this.userList.url = app.API + "Admin/GetUsersList/";
            this.userList.fetch({success: this.render});
        },

        click_add_btn: function()
        {
            MyPOS.ChangePage('admin/user/add');
        },

        click_edit_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-user-id');

            MyPOS.ChangePage('admin/user/modify/'+id);
        },

        click_delete_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-user-id');

            this.deleteId = id;

            $('#admin-user-delete-dialog').popup('open');
        },

        click_delete_finished_btn: function()
        {
            $('#admin-user-delete-dialog').popup('close');

            var webservice = new Webservice();
            webservice.action = "Admin/UserDelete";
            webservice.formData = {userid: this.deleteId};
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

            header.activeButton = 'user';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  users: this.userList});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminUserView;

} );