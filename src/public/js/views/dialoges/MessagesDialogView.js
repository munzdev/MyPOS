define(["Webservice",
        'text!templates/dialoges/messages-dialog.phtml'
], function(Webservice,
            Template)
{
    "use strict";
    
    return class MessagesDialogView extends app.PopupView
    {
        events() {
            return {'click #send': 'sendMessage',
                    'click #select-add': 'clickAddChat',
                    'change #select-add': 'clickAddChatOption',
                    'popupafteropen': 'popupafteropen',
                    'popupafterclose': 'popupafterclose',
                    'change #open-chats': 'switchChanel',
                    'keyup #message': 'onMessageInputKeyup'}
        }

        // The View Constructor
        initialize() {
            _.bindAll(this, "sendMessage",
                            "createOldMessages",
                            "checkMessagesStatus");

            this.currentChatChannel = '';

            this.userListInited = false;

            this.messages = {'':''};
            this.messagesUnreaded = {};

            this.isOpen = false;

            this.unreadedMessages = 0;

            $('body').pagecontainer({change: this.checkMessagesStatus});

            this.render();
        }

        onMessageInputKeyup(event) {
            if(event.key == 'Enter'){
                event.preventDefault();
                this.sendMessage();
                return false;
            }
        }

        fetchOldMessages() {
            var webservice = new Webservice();
            webservice.action = "Users_Messages/GetUsersMessages";
            webservice.call().done(this.createOldMessages);
        }

        createOldMessages(result) {
            var myId = app.auth.authUser.get('EventUser').get('EventUserid');

            _.each(result, (message) => {
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

                this.addMessage(channel, message.message, message.readed, isMe, message.date);
            });
        }

        popupafteropen() {
            if(!this.userListInited)
            {
                app.userList.each((user) => {
                    this.$('#select-add').append("<option value='" + user.get('EventUser').get('EventUserid') + "'>" + user.get('Firstname') + " " + user.get('Lastname')  + "</option>");
                })
                this.userListInited = true;
            }

            this.isOpen = true;
            this._updateChatText();
        }

        popupafterclose() {
            this.isOpen = false;
        }

        sendMessage() {
            var message = this.$('#message').val().trim();
            this.$('#message').val('');

            if(message == '')
                return;

            if(this.currentChatChannel == '')
            {
                app.error.showAlert("Es kann keine Nachricht an den Systembenutzer gesendet werden!");
                return;
            }

            app.ws.chat.Send(this.currentChatChannel, message);

            this.addMessage(this.currentChatChannel, message, true, true);
        }

        clickAddChat(event) {
            this.$(event.currentTarget).find("option[value='']").hide();
        }

        clickAddChatOption(event) {
            var target = this.$(event.currentTarget);
            var openChats = this.$('#open-chats');

            this._verifyChanelExists(target.val());

            openChats.val(target.val());
            openChats.change();

            target.find("option[value='']").show();
            target.val('').selectmenu('refresh');
        }

        switchChanel(event) {
            var openChats = this.$(event.currentTarget);

            this.currentChatChannel = openChats.val();

            this._updateChatText();
        }

        _verifyChanelExists(eventUserid) {
            var openChats = this.$('#open-chats');

            if(openChats.find("option[value='" + eventUserid + "']").length == 0)
            {
                var user = app.userList.find((user) => {return user.get('EventUser').get('EventUserid') == eventUserid});

                openChats.append("<option value='" + eventUserid + "'>" + user.get('Firstname') + " " + user.get('Lastname')  + "</option>");

                this.messages[eventUserid] = "";
            }
        }

        _setChanelUnreaded(channel, amount) {
            this._verifyChanelExists(channel);

            var option = this.$("#open-chats option[value='" +  channel + "']");
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
        }

        _updateChatText() {
            if(!this.isOpen)
                return;

            var textarea = this.$('#textarea');
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
        }

        checkMessagesStatus() {
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
        }

        _blinking(elm) {
            function blink() {
                elm.fadeOut(500, function() {
                    elm.fadeIn(500);
                });
            }
            return setInterval(blink, 1500);
        }

        addMessage(channel, message, readed, self, sendedDate) {
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
                user = app.auth.authUser;
                color = 'green';
            }
            else
            {
                user = app.userList.find((user) => {return user.get('EventUser').get('EventUserid') == channel});
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
                username = user.get('Firstname') + " " + user.get('Lastname');

            var time = $.format.date(date, "HH:mm:ss");

            this.messages[channel] += time +
                                     " <span  style='color: " + color + ";'>" +
                                     username +
                                     "</span>: " +
                                     $('<div/>').text(message).html() +
                                     "<br/>";

            if(this.currentChatChannel == channel)
                this._updateChatText();
        }

        render() {
            this.renderTemplate(Template);            

            this.checkMessagesStatus();
        }
    }

} );