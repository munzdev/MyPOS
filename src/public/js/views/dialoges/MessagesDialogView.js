// Options Dialog View
// =============

// Includes file dependencies
define(["app",
        "Webservice",
        'text!templates/dialoges/messages-dialog.phtml',
        "jquery-dateFormat"],
function(app, Webservice, Template )
{
    "use strict";

    // Extends Backbone.View
    var OptionsDialogView = Backbone.View.extend( {

    	title: 'messages-dialog',
    	el: 'body',
        events: {
            'click #messages-dialog-send': 'sendMessage',
            'click #messages-dialog-select-add': 'clickAddChat',
            'change #messages-dialog-select-add': 'clickAddChatOption',
            'popupafteropen': 'popupafteropen',
            'popupafterclose': 'popupafterclose',
            'change #messages-dialog-open-chats': 'switchChanel',
            'keyup #messages-dialog-message': 'onMessageInputKeyup'
        },

        // The View Constructor
        initialize: function()
        {
            _.bindAll(this, "sendMessage",
                            "createOldMessages",
                            "checkMessagesStatus");

            this.currentChatChannel = '';

            this.userListInited = false;

            this.messages = {'':''};
            this.messagesUnreaded = {};

            this.isOpen = false;

            this.unreadedMessages = 0;

            var self = this;

            $('body').pagecontainer({change: self.checkMessagesStatus});

            this.render();
        },

        onMessageInputKeyup: function(event)
        {
            if(event.key == 'Enter'){
                event.preventDefault();
                this.sendMessage();
                return false;
            }
        },

        fetchOldMessages: function()
        {
            var self = this;

            var webservice = new Webservice();
            webservice.action = "Users_Messages/GetUsersMessages";
            webservice.callback = {
                success: self.createOldMessages
            };
            webservice.call();
        },

        createOldMessages: function(result)
        {
            var self = this;

            var myId = app.session.user.get('events_userid');

            _.each(result, function(message) {
                var isMe = message.from_events_userid == myId;

                var channel;

                if(message.from_events_userid == null)
                {
                    channel = "";
                }
                else if(isMe)
                {
                    channel = message.to_events_userid;
                }
                else
                {
                    channel = message.from_events_userid;
                }

                self.addMessage(channel, message.message, message.readed, isMe, message.date);
            });
        },

        popupafteropen: function()
        {
            if(!this.userListInited)
            {
                app.session.userList.each(function(user) {
                    $('#messages-dialog-select-add').append("<option value='" + user.get('events_userid') + "'>" + user.get('firstname') + " " + user.get('lastname')  + "</option>");
                })
                this.userListInited = true;
            }

            this.isOpen = true;
            this._updateChatText();
        },

        popupafterclose: function()
        {
            this.isOpen = false;
        },

        sendMessage: function()
        {
            var message = $('#messages-dialog-message').val().trim();
            $('#messages-dialog-message').val('');

            if(message == '')
                return;

            if(this.currentChatChannel == '')
            {
                MyPOS.DisplayError("Es kann keine Nachricht an den Systembenutzer gesendet werden!");
                return;
            }

            app.ws.chat.Send(this.currentChatChannel, message);

            this.addMessage(this.currentChatChannel, message, true, true);
        },

        clickAddChat: function(event)
        {
            $(event.currentTarget).find("option[value='']").hide();
        },

        clickAddChatOption: function(event)
        {
            var target = $(event.currentTarget);
            var openChats = $('#messages-dialog-open-chats');

            this._verifyChanelExists(target.val());

            openChats.val(target.val());
            openChats.change();

            target.find("option[value='']").show();
            target.val('').selectmenu('refresh');
        },

        switchChanel: function(event)
        {
            var openChats = $(event.currentTarget);

            this.currentChatChannel = openChats.val();

            this._updateChatText();
        },

        _verifyChanelExists: function(events_userid)
        {
            var openChats = $('#messages-dialog-open-chats');

            if(openChats.find("option[value='" + events_userid + "']").length == 0)
            {
                var user = app.session.userList.findWhere({events_userid: events_userid});

                openChats.append("<option value='" + events_userid + "'>" + user.get('firstname') + " " + user.get('lastname')  + "</option>");

                this.messages[events_userid] = "";
            }
        },

        _setChanelUnreaded: function(channel, amount)
        {
            this._verifyChanelExists(channel);

            var option = $("#messages-dialog-open-chats option[value='" +  channel + "']");
            if(amount == 0)
            {
                option.html(option.attr('data-orginaltext'));
                option.removeAttr('data-orginaltext');
                option.removeAttr('style');
            }
            else
            {
                if(!option.attr('data-orginaltext'))
                {
                    option.attr('style', 'font-weight: bold;');
                    option.attr('data-orginaltext', option.text());
                }


                option.html(amount + " - " + option.attr('data-orginaltext'));
            }
        },

        _updateChatText: function()
        {
            if(!this.isOpen)
                return;

            var textarea = $('#messages-dialog-textarea');
            textarea.html(this.messages[this.currentChatChannel]);

            var webservice = new Webservice();
            webservice.formData = {channel: this.currentChatChannel};
            webservice.action = "Users_Messages/MarkReaded";
            webservice.call();

            if(this.messagesUnreaded[this.currentChatChannel])
            {
                var self = this;

                this.unreadedMessages -= this.messagesUnreaded[this.currentChatChannel];

                $('.navbar-header-messages-counter').each(function() {
                    $(this).html(self.unreadedMessages);
                });

                this.messagesUnreaded[this.currentChatChannel] = 0;
                this._setChanelUnreaded(this.currentChatChannel, 0);
                this.checkMessagesStatus();
            }

            if(textarea.length)
                textarea.scrollTop(textarea[0].scrollHeight - textarea.height());
        },

        checkMessagesStatus: function()
        {
            var self = this;

            $('.navbar-header-messages-counter').each(function() {
                if(parseInt($(this).text()) > 0)
                {
                    if(!$(this).attr('data-blinkingid'))
                    {
                        $(this).attr('data-blinkingid', self._blinking($(this)));
                    }
                }
                else
                {
                    clearInterval($(this).attr('data-blinkingid'));
                    $(this).removeAttr('data-blinkingid');
                }
            });
        },

        _blinking: function (elm) {
            function blink() {
                elm.fadeOut(500, function() {
                    elm.fadeIn(500);
                });
            }
            return setInterval(blink, 1500);
        },


        addMessage: function(channel, message, readed, self, sendedDate)
        {
            if(message == '')
                return;

            this._verifyChanelExists(channel);

            var user;
            var username;
            var color;
            if(channel == '')
            {
                username = "System"
                color = 'yellow';
            }
            else if(self)
            {
                user = app.session.user;
                color = 'green';
            }
            else
            {
                user = app.session.userList.findWhere({events_userid: channel});
                color = 'red';
            }

            if(!self && readed == '0')
            {
                this.unreadedMessages++;

                if(this.messagesUnreaded[channel])
                    this.messagesUnreaded[channel]++;
                else
                    this.messagesUnreaded[channel] = 1;

                this._setChanelUnreaded(channel, this.messagesUnreaded[channel]);

                $('.navbar-header-messages-counter').each(function() {
                    $(this).html(parseInt($(this).text()) + 1);
                });
                this.checkMessagesStatus();
            }

            var date = new Date().getTime();
            if(sendedDate) date = sendedDate;

            if(user)
                username = user.get('firstname') + " " + user.get('lastname');

            var time = $.format.date(date, "HH:mm:ss");

            this.messages[channel] += time +
                                     " <span  style='color: " + color + ";'>" +
                                     username +
                                     "</span>: " +
                                     $('<div/>').text(message).html() +
                                     "<br/>";

            if(this.currentChatChannel == channel)
                this._updateChatText();
        },

        // Renders all of the Category models on the UI
        render: function() {
            MyPOS.RenderPopupTemplate(this, this.title, Template, {}, 'data-theme="b"');

            $('#' + this.title).enhanceWithin().popup();

            this.checkMessagesStatus();

            return this;
        }
    });

    // Returns the View class
    return OptionsDialogView;

} );