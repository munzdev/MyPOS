// Login View
// =============

// Includes file dependencies
define([ 'collections/OrderOverviewCollection',
         'views/headers/HeaderView',
         'text!templates/pages/order-new.phtml'],
 function( OrderOverviewCollection,
           HeaderView,
           Template ) {
    "use strict";

    // Extends Backbone.View
    var OrderNewView = Backbone.View.extend( {

    	title: 'order-new',
    	el: 'body',

        // The View Constructor
        initialize: function() {
            _.bindAll(this, "render");

            this.render();
        },

        events: {
            "click .order-new-table-nr": 'tableNrClicked',
            "click #tableNrClear": "tableNrReset",
            "click #order-new-next": "orderNext"
        },

        tableNrClicked: function(event)
        {
            event.preventDefault();

            $('#tableNr').append($(event.currentTarget).html());
        },

        tableNrReset: function(event)
        {
            event.preventDefault();

            $('#tableNr').empty();
        },

        orderNext: function(event)
        {
            event.preventDefault();

            var tableNr = $('#tableNr').text();

            if(tableNr == '')
            {
                app.error.showAlert('Fehler!', 'Bitte gib eine Tischnummer an!');
                return;
            }

            MyPOS.ChangePage("#order-modify/id/new/tableNr/" + tableNr);
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();

            header.activeButton = 'order-new';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render()});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");

            return this;
        },
    } );

    // Returns the View class
    return OrderNewView;

} );