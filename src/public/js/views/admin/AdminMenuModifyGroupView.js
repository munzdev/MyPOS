// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'text!templates/pages/admin/admin-menu-modify-group.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminMenuModifyGroupView = Backbone.View.extend( {

    	title: 'admin-menu-modify-group',
    	el: 'body',
        events: {
            'click #admin-menu-modify-group-save-btn': 'click_save_btn'
        },

        click_save_btn: function()
        {
            var name = $.trim($('#admin-menu-modify-group-name').val());

            if(name == '')
            {
                MyPOS.DisplayError('Bitte einen Namen eingeben!');
                return;
            }

            if(this.mode == 'new')
            {
                var webservice = new Webservice();
                webservice.action = "Admin/AddMenuGroup";
                webservice.formData = {name: name,
                                       menu_typeid: this.menu_typeid};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/menu');
                    }
                };
                webservice.call();
            }
            else
            {
                var webservice = new Webservice();
                webservice.action = "Admin/SetMenuGroup";
                webservice.formData = {id: this.groupid,
                                       name: name};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/menu');
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

            this.nameValue = "";
            this.menu_typeid = options.menu_typeid;

            if(options.id === 'new')
            {
                this.mode = 'new';
                this.groupid = 0;
                this.render();
            }
            else
            {
                this.mode = 'edit';
                this.groupid = options.id;

                var webservice = new Webservice();
                webservice.action = "Admin/GetMenuGroup";
                webservice.formData = {id: this.groupid};
                webservice.callback = {
                    success: function(data)
                    {
                        self.nameValue = data.name;
                        self.render();
                    }
                };
                webservice.call();
            }
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();

            header.activeButton = 'menu';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  mode: this.mode,
                                                                  name: this.nameValue});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminMenuModifyGroupView;

} );