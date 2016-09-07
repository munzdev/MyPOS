// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'text!templates/pages/admin/admin-table-modify.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminTableModifyView = Backbone.View.extend( {

    	title: 'admin-table-modify',
    	el: 'body',
        events: {
            'click #admin-table-modify-save-btn': 'click_save_btn'
        },

        click_save_btn: function()
        {
            var name = $.trim($('#admin-table-modify-name').val());
            var data = $.trim($('#admin-table-modify-data').val());

            if(name == '')
            {
                MyPOS.DisplayError('Bitte eine Tischnummer eingeben!');
                return;
            }

            if(this.mode == 'new')
            {
                var webservice = new Webservice();
                webservice.action = "Admin/AddTable";
                webservice.formData = {name: name,
                                       data: data};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/table');
                    }
                };
                webservice.call();
            }
            else
            {
                var webservice = new Webservice();
                webservice.action = "Admin/SetTable";
                webservice.formData = {tableid: this.tableid,
                                       name: name,
                                       data: data};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/table');
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
            this.dataValue = "";

            if(options.id === 'new')
            {
                this.mode = 'new';
                this.tableid = 0;
                this.render();
            }
            else
            {
                this.mode = 'edit';
                this.tableid = options.id;



                var webservice = new Webservice();
                webservice.action = "Admin/GetTable";
                webservice.formData = {tableid: this.tableid};
                webservice.callback = {
                    success: function(data)
                    {
                        self.nameValue = data.name;
                        self.dataValue = data.data;
                        self.render();
                    }
                };
                webservice.call();
            }
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();

            header.activeButton = 'table';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  mode: this.mode,
                                                                  name: this.nameValue,
                                                                  data: this.dataValue
                                                                  });

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminTableModifyView;

} );