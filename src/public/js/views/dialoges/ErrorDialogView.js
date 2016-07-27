// Login View
// =============

// Includes file dependencies
define([ "app", "MyPOS", 'text!templates/dialoges/error-dialog.phtml'], function( app, MyPOS, Template ) {

    // Extends Backbone.View
    var ErrorDialogView = Backbone.View.extend( {

    	title: 'error-dialog',
    	el: 'body',
        events: {
        },

        // The View Constructor
        initialize: function() {
            this.render();
        },

        // Renders all of the Category models on the UI
        render: function() {
            MyPOS.RenderDialogeTemplate(this, this.title, Template);

            $("#error-dialog-close").click(function(evt) {
            	evt.preventDefault();
            	$.mobile.changePage( $("#error-dialog-close").attr('href'), { transition: "flip" });
            });

            return this;
        },

        // Show alert classes and hide after specified timeout
        showAlert: function(title, text) {
            $("#error-dialog-header").html(title);
            $("#error-dialog-content").html(text);
            $("#error-dialog-close").attr("href", '#' + $.mobile.activePage.attr('id'));
            $.mobile.changePage( "#" + this.title, { transition: "flip" });
        }
    });

    // Returns the View class
    return ErrorDialogView;

} );