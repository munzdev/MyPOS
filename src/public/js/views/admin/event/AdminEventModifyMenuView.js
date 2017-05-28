// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'views/headers/AdminHeaderView',
         'views/footers/AdminFooterView',
         'collections/product/TypeCollection',
         'text!templates/pages/admin/admin-event-modify-menu.phtml'],
function( Webservice,
          AdminHeaderView,
          AdminFooterView,
          ProductCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminEventModifyMenuView = Backbone.View.extend( {

    	title: 'admin-event-modify-menu',
    	el: 'body',
        events: {
            'click .admin-event-modify-menu-add-btn': 'click_menu_add',
            'click .admin-event-modify-menu-item': 'click_menu_item',
            'click #admin-event-modify-menu-item-edit': 'click_menu_edit',
            'click #admin-event-modify-menu-item-delete': 'click_menu_delete',
            'click #admin-event-modify-menu-item-delete-finish-btn': 'click_menu_delete_finish'
        },

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render");

            this.id = options.id;

            this.productsList = new ProductCollection();
            this.productsList.url = app.API + "Admin/GetEventMenuList/";
            this.productsList.fetch({data: {eventid: this.id},
                                     success: this.render});
        },

        click_menu_add: function(event)
        {
            var id = $(event.currentTarget).attr('data-group-id');

            MyPOS.ChangePage('#admin/event/modify/' + this.id + '/menu/add/' + id);
        },

        click_menu_item: function(event)
        {
            var id = $(event.currentTarget).attr('data-menu-id');

            this.editId = id;

            $('#admin-event-modify-menu-item-dialog').popup('open', {x: event.pageX,
                                                                     y: event.pageY});
        },

        click_menu_edit: function(event)
        {
            MyPOS.ChangePage('#admin/event/modify/' + this.id + '/menu/' + this.editId);
        },

        click_menu_delete: function(event)
        {
            $('#admin-event-modify-menu-item-dialog').popup('close');
            $('#admin-event-modify-menu-item-delete-dialog').popup('open');
        },

        click_menu_delete_finish: function()
        {
            $('#admin-event-modify-menu-item-delete-dialog').popup('close');

            var webservice = new Webservice();
            webservice.action = "Admin/DeleteMenu";
            webservice.formData = {id: this.editId};
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
            footer.activeButton = 'menu';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  footer: footer.render(),
                                                                  products: this.productsList});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminEventModifyMenuView;

} );