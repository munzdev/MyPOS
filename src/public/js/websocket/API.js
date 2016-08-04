// Login Model
// ----------

//Includes file dependencies
define([ "app", "wampy"], function()
{
    "use strict";

    function API()
    {
        var location = window.location;

        this.websericeUrl = ((location.protocol === "https:") ? "wss://" : "ws://") +
                            location.hostname +
                            ":8080/api";

        this.ws = new Wampy({ maxRetries: 1000,
                              autoReconnect: true,
                              reconnectInterval: 2000,
                              onConnect: this.onConnect,
                              onClose: this.onClose,
                              onReconnect: function () { console.log('Reconnecting...'); },
                              onError: function () { console.log('Breakdown happened'); }});
    }

    API.prototype.Connect = function()
    {
        this.ws.connect(this.websericeUrl);
    }

    API.prototype.Disconnect = function()
    {
        this.ws.disconnect();
    }

    API.prototype.onConnect = function ()
    {
        // WAMP session established here ..
        if(DEBUG) console.log("API-Verbindung hergestellt!");
    }

    API.prototype.onClose = function ()
    {
        if(DEBUG) console.log("API-Verbindung getrennt!");
    }

    return API;

} );
