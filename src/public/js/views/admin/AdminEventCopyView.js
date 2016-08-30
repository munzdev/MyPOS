// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'text!templates/pages/admin/admin-event-copy.phtml'],
function( app,
          Webservice,
          AdminHeaderView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminEventCopyView = Backbone.View.extend( {

    	title: 'admin-event-copy',
    	el: 'body',
        events: {
            'click #admin-event-copy-finished-btn': 'click_finished_btn'
        },

        click_finished_btn: function()
        {
            var name = $.trim($('#admin-event-copy-name').val());
            var date = $.trim($('#admin-event-copy-date').val());

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


        },

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render");

            var self = this;

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
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();

            header.activeButton = 'event';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  events: this.eventsList,
                                                                  name: this.nameValue,
                                                                  date: this.dateValue,});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminEventCopyView;

} );