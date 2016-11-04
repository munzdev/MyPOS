define(["wampy"], function() {
    "use strict";
    
    return class WebsocketClient
    {
        constructor(url)
        {
            _.bindAll(this, "onConnect",
                            "_unregisterChanel");

            var location = window.location;
            
            this.url = url;

            this.websericeUrl = ((location.protocol === "https:") ? "wss://" : "ws://") +
                                location.hostname +
                                ":8080/" + url;

            this.ws = new Wampy({ maxRetries: 1000,
                                  autoReconnect: true,
                                  reconnectInterval: 2000,
                                  onConnect: this.onConnect,
                                  onClose: this.onClose,
                                  onReconnect: function () { console.log('Reconnecting...'); },
                                  onError: this._unregisterChanel});
        }
        
        Connect()
        {
            this.ws.connect(this.websericeUrl);
        }
        
        onConnect()
        {
            // WAMP session established here ..
            if(DEBUG) console.log(this.url + "-Verbindung hergestellt!");

            this._registerChanel();
        }
        
        _registerChanel()
        {
        }
        
        _unregisterChanel()
        {
        }
        
        Disconnect()
        {
            this._unregisterChanel();
            this.ws.disconnect();
        }
        
        onClose()
        {
            if(DEBUG) console.log(this.url + "-Verbindung getrennt!");
        }
    }

} );
