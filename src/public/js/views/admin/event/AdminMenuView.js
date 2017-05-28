// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'views/headers/AdminHeaderView',
         'collections/admin/MenuTypeCollection',
         'text!templates/pages/admin/admin-menu.phtml'],
function( Webservice,
          AdminHeaderView,
          MenuTypeCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminMenuView = Backbone.View.extend( {

    	title: 'admin-menu',
    	el: 'body',
        events: {
            'click #admin-menu-typ-add-btn': 'click_btn_typ_add',
            'click .admin-menu-typ-edit-btn': 'click_btn_typ_edit',
            'click .admin-menu-typ-delete-btn': 'click_btn_typ_delete',
            'click .admin-menu-group-add-btn': 'click_btn_group_add',
            'click .admin-menu-group-item': 'click_menu_group_item',
            'click #admin-menu-group-item-edit': 'click_menu_group_edit',
            'click #admin-menu-group-item-delete': 'click_menu_group_delete',
            'click #admin-menu-item-delete-finish-btn': 'click_delete_finish'
        },

        // The View Constructor
        initialize: function() {
            _.bindAll(this, "render");

            this.menuList = new MenuTypeCollection();
            this.menuList.fetch({success: this.render});
        },

        click_btn_typ_add: function()
        {
            MyPOS.ChangePage('#admin/menu/add');
        },

        click_btn_typ_edit: function(event)
        {
            var id = $(event.currentTarget).parent().attr('data-type-id');

            MyPOS.ChangePage('#admin/menu/modify/type/' + id);
        },

        click_btn_typ_delete: function(event)
        {
            var id = $(event.currentTarget).parent().attr('data-type-id');

            this.editId = id;
            this.deleteHandler = this.deleteTyp;

            $('#admin-menu-item-delete-dialog').popup('open');
        },

        deleteTyp: function()
        {
            $('#admin-menu-item-delete-dialog').popup('close');

            var webservice = new Webservice();
            webservice.action = "Admin/DeleteMenuType";
            webservice.formData = {id: this.editId};
            webservice.callback = {
                success: function()
                {
                    MyPOS.ReloadPage();
                }
            };
            webservice.call();
        },

        click_menu_group_item: function(event)
        {
            var id = $(event.currentTarget).attr('data-group-id');

            this.editId = id;

            $('#admin-menu-item-dialog').popup('open', {x: event.pageX,
                                                        y: event.pageY});
        },

        click_btn_group_add: function(event)
        {
            var id = $(event.currentTarget).parent().attr('data-type-id');

            MyPOS.ChangePage('#admin/menu/add/' + id);
        },


        click_menu_group_delete: function(event)
        {
            $('#admin-menu-item-dialog').popup('close');

            this.deleteHandler = this.deleteGroup;

            $('#admin-menu-item-delete-dialog').popup('open');
        },

        deleteGroup: function()
        {
            $('#admin-menu-item-delete-dialog').popup('close');

            var webservice = new Webservice();
            webservice.action = "Admin/DeleteMenuGroup";
            webservice.formData = {id: this.editId};
            webservice.callback = {
                success: function()
                {
                    MyPOS.ReloadPage();
                }
            };
            webservice.call();
        },

        click_menu_group_edit: function(event)
        {
            $('#admin-menu-item-dialog').popup('close');
            MyPOS.ChangePage('#admin/menu/modify/group/' + this.editId);
        },

        click_delete_finish: function()
        {
            this.deleteHandler();
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();

            header.activeButton = 'menu';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  products: this.menuList});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminMenuView;

} );