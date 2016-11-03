// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'views/headers/AdminHeaderView',
         'views/footers/AdminFooterView',
         'collections/admin/DistributionPlaceCollection',
         'text!templates/pages/admin/admin-event-modify-distribution.phtml'],
function( Webservice,
          AdminHeaderView,
          AdminFooterView,
          DistributionPlaceCollection,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var AdminEventModifyDistributionView = Backbone.View.extend( {

    	title: 'admin-event-modify-distribution',
    	el: 'body',
        events: {
            'click #admin-event-modify-distribution-add-btn': 'click_add_btn',
            'click .admin-event-modify-distribution-edit-btn': 'click_edit_btn',
            'click .admin-event-modify-distribution-delete-btn': 'click_delete_btn',
            'click #admin-event-modify-distribution-delete-dialog-finished': 'click_delete_finished_btn'
        },

        // The View Constructor
        initialize: function(options) {
            _.bindAll(this, "render");

            this.id = options.id;

            this.distributionList = new DistributionPlaceCollection();
            this.distributionList.fetch({data: {eventid: this.id},
                                        success: this.render});
        },

        click_add_btn: function()
        {
            MyPOS.ChangePage('#admin/event/modify/' + this.id + '/distribution/add');
        },

        click_edit_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-distribution-id');

            MyPOS.ChangePage('#admin/event/modify/' + this.id + '/distribution/modify/' + id);
        },

        click_delete_btn: function(event)
        {
            var id = $(event.currentTarget).attr('data-distribution-id');

            this.deleteId = id;

            $('#admin-event-modify-distribution-delete-dialog').popup('open');
        },

        click_delete_finished_btn: function()
        {
            $('#admin-event-modify-distribution-delete-dialog').popup('close');

            var webservice = new Webservice();
            webservice.action = "Admin/DeleteEventDistribution";
            webservice.formData = {distributions_placeid: this.deleteId};
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
            footer.activeButton = 'distribution';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  footer: footer.render(),
                                                                  distributions: this.distributionList});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return AdminEventModifyDistributionView;

} );