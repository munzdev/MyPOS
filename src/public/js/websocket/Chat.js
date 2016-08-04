// Login Model
// ----------

//Includes file dependencies
define([ "app", "wampy"], function()
{
    "use strict";

    function Chat()
    {
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
                              onError: function () { console.log('Breakdown happened'); }});
    }

    Chat.prototype.Connect = function()
    {
        this.ws.connect(this.websericeUrl);
    }

    Chat.prototype.Disconnect = function()
    {
        this.ws.disconnect();
    }

    Chat.prototype.onConnect = function ()
    {
        // WAMP session established here ..
        if(DEBUG) console.log("Chat-Verbindung hergestellt!");
    }

    Chat.prototype.onClose = function ()
    {
        if(DEBUG) console.log("Chat-Verbindung getrennt!");
    }

    return Chat;

} );
