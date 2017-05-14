// Login View
// =============

// Includes file dependencies
define([ 'Webservice',
         'collections/manager/UserRoleCollection',
         'views/headers/HeaderView',
         'views/footers/ManagerFooterView',
         'text!templates/pages/manager-groupmessage.phtml'],
function( Webservice,
          UserRoleCollection,
          HeaderView,
          ManagerFooterView,
          Template ) {
    "use strict";

    // Extends Backbone.View
    var ManagerGroupmessageView = Backbone.View.extend( {

    	title: 'manager-groupmessage',
    	el: 'body',
        events: {
            'click #manager-groupmessage-send': 'click_btn_send',
            'keyup #manager-groupmessage-message': 'onMessageKeyup'
        },

        // The View Constructor
        initialize: function() {
            _.bindAll(this, "render");

            this.userRoleCollection = new UserRoleCollection();
            this.userRoleCollection.fetch({success: this.render});
        },

        onMessageKeyup: function(evt)
        {
            var k = evt.keyCode || evt.which;

            if (k == 13 && $('#manager-groupmessage-message').val() === ''){
                evt.preventDefault();    // prevent enter-press submit when input is empty
            } else if(k == 13){
                evt.preventDefault();
                this.click_btn_send();
                return false;
            }
        },

        click_btn_send: function()
        {
            var message = $('#manager-groupmessage-message').val().trim();
            var userRoles = $("#manager-groupmessage-userRoles input[name='userRole']:checked");
            var my_userid = app.session.user.get('userid');

            if(userRoles.length == 0)
            {
                MyPOS.DisplayError("Bitte Ziel Benutzergruppen auswählen!");
                return;
            }

            $('#manager-groupmessage-message').val('');

            if(message == '')
                return;

            userRoles.each(function(userRole)
            {
                app.ws.api.Trigger('manager-groupmessage', {callRes: function (data) {

                    $.each(data, function(index, userid) {

                        if(userid != my_userid)
                        {
                            app.ws.chat.Send(userid, message);
                            app.session.messagesDialog.addMessage(userid, message, true, true);
                        }
                    });
                }}, $(this).val());
            });
        },

        // Renders all of the Category models on the UI
        render: function() {
            var header = new HeaderView();
            var footer = new ManagerFooterView();

            header.activeButton = 'manager';
            footer.activeButton = 'groupmessage';

            MyPOS.RenderPageTemplate(this, this.title, Template, {userRoleCollection: this.userRoleCollection,
                                                                  header: header.render(),
                                                                  footer: footer.render()});

            this.setElement("#" + this.title);
            header.setElement("#" + this.title + " .nav-header");
            footer.setElement("#" + this.title + " .nav-footer");

            $.mobile.changePage( "#" + this.title);
            return this;
        }

    } );

    // Returns the View class
    return ManagerGroupmessageView;

} );