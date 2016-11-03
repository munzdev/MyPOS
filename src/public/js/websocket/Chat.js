// Login Model
// ----------

//Includes file dependencies
define(["wampy"], function()
{
    "use strict";

    function Chat()
    {
        _.bindAll(this, "onConnect",
                        "_unregisterChanel");

        var location = window.location;

        this.websericeUrl = ((location.protocol === "https:") ? "wss://" : "ws://") +
                            location.hostname +
                            ":8080/chat";

        this.ws = new Wampy({ maxRetries: 1000,
                              autoReconnect: true,
                              reconnectInterval: 2000,
                              onConnect: this.onConnect,
                              onClose: this.onClose,
                              onReconnect: function () { console.log('Reconnecting...'); },
                              onError: this._unregisterChanel});
    }

    Chat.prototype.Connect = function()
    {
        this.ws.connect(this.websericeUrl);
    }

    Chat.prototype._registerChanel = function()
    {
        if(DEBUG) console.log("REGISTERED TO CHANEL: " + app.session.user.get('events_userid'));

        this.ws.subscribe(app.session.user.get('events_userid'),
                          this._messageRecieved);
    }

    Chat.prototype._unregisterChanel = function()
    {
        if(DEBUG) console.log("UNREGISTERED TO CHANEL: " + app.session.user.get('userid'));

        this.ws.unsubscribe(app.session.user.get('events_userid'));
    }

    Chat.prototype._messageRecieved = function(data)
    {
        console.log("DATA RECIEVED: " + data);

        var message_data = JSON.parse(data);

        app.session.messagesDialog.addMessage(message_data.sender, message_data.message, false, false);
    }

    Chat.prototype.Send = function(userid, message)
    {
        this.ws.publish(userid, message);
    }

    Chat.prototype.SystemMessage = function(userid, message)
    {
        this.ws.call("systemMessage", {}, userid, message);
    }

    Chat.prototype.Disconnect = function()
    {
        this._unregisterChanel();
        this.ws.disconnect();
    }

    Chat.prototype.onConnect = function ()
    {
        // WAMP session established here ..
        if(DEBUG) console.log("Chat-Verbindung hergestellt!");

        this._registerChanel();
    }

    Chat.prototype.onClose = function ()
    {
        if(DEBUG) console.log("Chat-Verbindung getrennt!");
    }

    return Chat;

} );
