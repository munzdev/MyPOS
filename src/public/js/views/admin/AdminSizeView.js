// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'collections/products/SizeCollection',
         'text!templates/pages/admin/admin-size.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          SizeCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminSizeView = Backbone.View.extend( {

    	title: 'admin-size',
    	el: 'body',
        events: {
            'click #admin-size-add-btn': 'click_add_btn',
            'click .admin-size-edit-btn': 'click_edit_btn',
            'click .admin-size-delete-btn': 'click_delete_btn',
            'click #admin-size-delete-dialog-finished': 'click_delete_finished_btn'
        },

        // The View Constructor
        initialize: function() {
            _.bindAll(this, "render");

            this.sizeList = new SizeCollection();
            this.sizeList.url = app.API + "Admin/GetMenuSizes/";
            this.sizeList.fetch({success: this.render});
        },

        click_add_btn: function()
        {
            MyPOS.ChangePage('admin/size/add');
        },

        click_edit_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-size-id');

            MyPOS.ChangePage('admin/size/modify/'+id);
        },

        click_delete_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-size-id');

            this.deleteId = id;

            $('#admin-size-delete-dialog').popup('open');
        },

        click_delete_finished_btn: function()
        {
            $('#admin-size-delete-dialog').popup('close');

            var webservice = new Webservice();
            webservice.action = "Admin/DeleteSize";
            webservice.formData = {menu_sizeid: this.deleteId};
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

            header.activeButton = 'size';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  sizes: this.sizeList});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminSizeView;

} );