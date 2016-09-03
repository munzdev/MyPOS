// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'collections/admin/MenuTypeCollection',
         'text!templates/pages/admin/admin-menu.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          MenuTypeCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminMenuView = Backbone.View.extend( {

    	title: 'admin-menu',
    	el: 'body',
        events: {

        },

        // The View Constructor
        initialize: function() {
            _.bindAll(this, "render");

            this.menuList = new MenuTypeCollection();
            this.menuList.fetch({success: this.render});
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