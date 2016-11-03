// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'views/headers/AdminHeaderView',
         'text!templates/pages/admin/admin-menu-modify-type.phtml'],
function( Webservice,
          AdminHeaderView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminMenuModifyTypeView = Backbone.View.extend( {

    	title: 'admin-menu-modify-type',
    	el: 'body',
        events: {
            'click #admin-menu-modify-type-save-btn': 'click_save_btn'
        },

        click_save_btn: function()
        {
            var name = $.trim($('#admin-menu-modify-type-name').val());
            var tax = $.trim($('#admin-menu-modify-type-tax').val());
            var allowMixing = $('#admin-menu-modify-type-allowMixing').prop('checked');

            if(name == '')
            {
                MyPOS.DisplayError('Bitte einen Namen eingeben!');
                return;
            }

            if(tax == '' || tax < 0 || tax > 100)
            {
                MyPOS.DisplayError('Bitte einen gültigen Steuersatz eingeben! (0 - 100%)');
                return;
            }

            if(this.mode == 'new')
            {
                var webservice = new Webservice();
                webservice.action = "Admin/AddMenuType";
                webservice.formData = {name: name,
                                       tax: tax,
                                       allowMixing: allowMixing};
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
                webservice.action = "Admin/SetMenuType";
                webservice.formData = {id: this.typeid,
                                       name: name,
                                       tax: tax,
                                       allowMixing: allowMixing};
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
            this.taxValue = "";
            this.allowMixingValue = 0;

            if(options.id === 'new')
            {
                this.mode = 'new';
                this.typeid = 0;
                this.render();
            }
            else
            {
                this.mode = 'edit';
                this.typeid = options.id;

                var webservice = new Webservice();
                webservice.action = "Admin/GetMenuType";
                webservice.formData = {id: this.typeid};
                webservice.callback = {
                    success: function(data)
                    {
                        self.nameValue = data.name;
                        self.taxValue = data.tax;
                        self.allowMixingValue = data.allowMixing;
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
                                                                  name: this.nameValue,
                                                                  tax: this.taxValue,
                                                                  allowMixing: this.allowMixingValue});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminMenuModifyTypeView;

} );