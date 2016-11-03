// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'views/headers/AdminHeaderView',
         'views/footers/AdminFooterView',
         'text!templates/pages/admin/admin-event-modify-detail.phtml',
         'jquerymobile-datebox'],
function( Webservice,
          AdminHeaderView,
          AdminFooterView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminEventModifyDetailView = Backbone.View.extend( {

    	title: 'admin-event-modify-details',
    	el: 'body',
        events: {
            'click #admin-event-modify-details-save-btn': 'click_save_btn'
        },

        click_save_btn: function()
        {
            var name = $.trim($('#admin-event-modify-details-name').val());
            var date = $.trim($('#admin-event-modify-details-date').val());

            if(name == '')
            {
                MyPOS.DisplayError('Bitte einen Namen eingeben!');
                return;
            }

            if(date == '')
            {
                MyPOS.DisplayError('Bitte ein Datum auswählen!');
                return;
            }

            if(this.mode == 'new')
            {
                var webservice = new Webservice();
                webservice.action = "Admin/AddEvent";
                webservice.formData = {name: name,
                                       date: date};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/event');
                    }
                };
                webservice.call();
            }
            else
            {
                var webservice = new Webservice();
                webservice.action = "Admin/SetEvent";
                webservice.formData = {eventid: this.eventid,
                                       name: name,
                                       date: date};
                webservice.callback = {
                    success: function()
                    {
                        MyPOS.ChangePage('#admin/event');
                    }
                };
                webservice.call();
            }


        },

        // The View Constructor
        initialize: function(options) {
            //_.bindAll(this, "render");

            var self = this;

            this.nameValue = "";
            this.dateValue = "";

            if(options.id === 'new')
            {
                this.mode = 'new';
                this.eventid = 0;
                this.render();
            }
            else
            {
                this.mode = 'edit';
                this.eventid = options.id;



                var webservice = new Webservice();
                webservice.action = "Admin/GetEvent";
                webservice.formData = {eventid: this.eventid};
                webservice.callback = {
                    success: function(data)
                    {
                        self.nameValue = data.name;
                        self.dateValue = data.date;
                        self.render();
                    }
                };
                webservice.call();
            }
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();
            var footer = new AdminFooterView({id: this.eventid});

            header.activeButton = 'event';
            footer.activeButton = 'detail';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  footer: footer.render(),
                                                                  name: this.nameValue,
                                                                  date: this.dateValue,
                                                                  mode: this.mode});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminEventModifyDetailView;

} );