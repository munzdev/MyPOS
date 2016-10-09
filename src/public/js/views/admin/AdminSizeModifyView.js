// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'text!templates/pages/admin/admin-size-modify.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminSizeModifyView = Backbone.View.extend( {

    	title: 'admin-size-modify',
    	el: 'body',
        events: {
            'click #admin-size-modify-save-btn': 'click_save_btn'
        },

        click_save_btn: function()
        {
            var name = $.trim($('#admin-size-modify-name').val());
            var factor = $.trim($('#admin-size-modify-factor').val());

            if(name == '')
            {
                MyPOS.DisplayError('Bitte einen Namen eingeben!');
                return;
            }
            
            if(factor == '')
            {
                MyPOS.DisplayError('Bitte einen Faktor eingeben!');
                return;
            }

            if(this.mode == 'new')
            {
                var webservice = new Webservice();
                webservice.action = "Admin/AddSize";
                webservice.formData = {name: name,
                                       factor: factor};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/size');
                    }
                };
                webservice.call();
            }
            else
            {
                var webservice = new Webservice();
                webservice.action = "Admin/SetSize";
                webservice.formData = {menu_sizeid: this.sizeid,
                                       name: name,
                                       factor: factor};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/size');
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
            this.factorValue = "";

            if(options.id === 'new')
            {
                this.mode = 'new';
                this.sizeid = 0;
                this.render();
            }
            else
            {
                this.mode = 'edit';
                this.sizeid = options.id;

                var webservice = new Webservice();
                webservice.action = "Admin/GetSize";
                webservice.formData = {menu_sizeid: this.sizeid};
                webservice.callback = {
                    success: function(data)
                    {
                        self.nameValue = data.name;
                        self.factorValue = data.factor;
                        self.render();
                    }
                };
                webservice.call();
            }
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();

            header.activeButton = 'size';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  mode: this.mode,
                                                                  name: this.nameValue,
                                                                  factor: this.factorValue
                                                                  });

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminSizeModifyView;

} );