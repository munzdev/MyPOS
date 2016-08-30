// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'views/headers/AdminHeaderView',
         'text!templates/pages/admin/admin-event-modify-details.phtml',
         'jquerymobile-datebox'],
function( app,
          Webservice,
          AdminHeaderView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminEventModifyDetailsView = Backbone.View.extend( {

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
        },

        // The View Constructor
        initialize: function(options) {
            //_.bindAll(this, "render");

            if(options.id === 'new')
            {
                this.mode = 'new';
            }
            else
            {
                this.mode = 'edit';
                this.eventid = options.id;
            }

            this.render();
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new AdminHeaderView();

            header.activeButton = 'event';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  mode: this.mode});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminEventModifyDetailsView;

} );