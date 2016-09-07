// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'collections/admin/TableCollection',
         'text!templates/pages/admin/admin-table.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          TableCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminTableView = Backbone.View.extend( {

    	title: 'admin-table',
    	el: 'body',
        events: {
            'click #admin-table-add-btn': 'click_add_btn',
            'click .admin-table-edit-btn': 'click_edit_btn',
            'click .admin-table-delete-btn': 'click_delete_btn',
            'click #admin-table-delete-dialog-finished': 'click_delete_finished_btn'
        },

        // The View Constructor
        initialize: function() {
            _.bindAll(this, "render");

            this.tableList = new TableCollection();
            this.tableList.fetch({success: this.render});
        },

        click_add_btn: function()
        {
            MyPOS.ChangePage('admin/table/add');
        },

        click_edit_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-table-id');

            MyPOS.ChangePage('admin/table/modify/'+id);
        },

        click_delete_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-table-id');

            this.deleteId = id;

            $('#admin-table-delete-dialog').popup('open');
        },

        click_delete_finished_btn: function()
        {
            $('#admin-table-delete-dialog').popup('close');

            var webservice = new Webservice();
            webservice.action = "Admin/TableDelete";
            webservice.formData = {tableid: this.deleteId};
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

            header.activeButton = 'table';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  tables: this.tableList});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminTableView;

} );