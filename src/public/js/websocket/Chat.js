define(["websocket/WebsocketClient"
], function(WebsocketClient) {
    "use strict";
    
    return class Chat extends WebsocketClient
    {
        constructor()
        {
            super("Chat");
        }
        
        _registerChanel()
        {
            var eventUserid = app.auth.authUser.get('EventUser').get('EventUserid').toString();
            
            if(DEBUG) console.log("REGISTERED TO CHANEL: " + eventUserid);

            this.ws.subscribe(eventUserid,
                              this._messageRecieved);
        }
        
        _unregisterChanel()
        {
            if(DEBUG) console.log("UNREGISTERED TO CHANEL: " + app.auth.authUser.get('Userid'));

            this.ws.unsubscribe(app.auth.authUser.get('EventUser').get('EventUserid'));
        }
        
        _messageRecieved(data)
        {
            console.log("DATA RECIEVED: " + data);

            var message_data = JSON.parse(data);

            app.messagesDialog.addMessage(message_data.sender, message_data.message, false, false);
        }
        
        Send(userid, message)
        {
            this.ws.publish(userid, message);
        }
        
        SystemMessage(userid, message)
        {
            this.ws.call("systemMessage", {}, userid, message);
        }        
    }

} );
