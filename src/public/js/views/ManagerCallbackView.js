// Login View
// =============

// Includes file dependencies
define([ "app",
         'Webservice',
         'collections/manager/CallbackCollection',
         'views/headers/HeaderView',
         'views/footers/ManagerFooterView',
         'text!templates/pages/manager-callback.phtml',
         "jquery-dateFormat"],
function( app,
          Webservice,
          CallbackCollection,
          HeaderView,
          ManagerFooterView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var ManagerCallbackView = Backbone.View.extend( {

    	title: 'manager-callback',
    	el: 'body',
        events: {
            'click .manager-callback-done-btn': 'click_btn_done'
        },

        // The View Constructor
        initialize: function() {
            _.bindAll(this, "render");

            this.callbackCollection = new CallbackCollection();
            this.callbackCollection.fetch({success: this.render});
        },

        click_btn_done: function(event)
        {
            var userid = $(event.currentTarget).attr('data-user-id');

            var webservice = new Webservice();
            webservice.action = "Manager/ResetCallback";
            webservice.formData = {userid: userid};
            webservice.callback = {
                success: function() {

                    app.ws.chat.SystemMessage(userid, "Ihr Rückruf wurde als erledigt markiert von " + app.session.user.get('firstname') + " " + app.session.user.get('lastname'));

                    MyPOS.ReloadPage();
                }
            };
            webservice.call();
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();
            var footer = new ManagerFooterView();

            header.activeButton = 'manager';
            footer.activeButton = 'callback';

            MyPOS.RenderPageTemplate(this, this.title, Template, {header: header.render(),
                                                                  footer: footer.render(),
                                                                  callbacks: this.callbackCollection});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return ManagerCallbackView;

} );