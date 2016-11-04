define(["websocket/WebsocketClient", 
        "collections/db/User/UserRoleCollection"
], function(WebsocketClient, 
            UserRoleCollection) {
    "use strict";
    
    return class API extends WebsocketClient
    {
        constructor()
        {
            super("API");
            this.rolesSubscribed = [];
        }
        
        _registerChanel()
        {
            var userRolesCollection = new UserRoleCollection();
            userRolesCollection.fetch()
                    .done(() => {
                        var userRoles = app.auth.authUser.get('EventUser').get('UserRoles');
                        var userid = app.auth.authUser.get('Userid');

                        userRolesCollection.each((role) => {
                            if(userRoles & role.get('UserRoleid'))
                            {
                                if(DEBUG) console.log("REGISTERED TO API ROLE: " + role.get('Name'));

                                var chanelName = userid + "-" + role.get('UserRoleid');

                                this.rolesSubscribed.push(chanelName);

                                this.ws.subscribe(chanelName,
                                                  this._commandRecieved);
                            }
                        });
                    });
        }
        
        _unregisterChanel()
        {
            _.each(this.rolesSubscribed, (chanelName) => {
                if(DEBUG) console.log("UNREGISTERED TO API ROLE: " + chanelName);

                this.ws.unsubscribe(chanelName);
            }); 
        }
        
        _commandRecieved(data)
        {
            if(DEBUG) console.log("API DATA RECIEVED: " + data);

            var commandData = JSON.parse(data);

            if('apiCommandReciever' in app.router.currentView)
            {
                app.router.currentView.apiCommandReciever(commandData.command, commandData.options);
            }

            if(typeof commandData.options !== 'undefined' && commandData.options.systemMessage)
            {
                app.ws.chat.SystemMessage(app.auth.authUser.get('Userid'), commandData.options.systemMessage);
            }

            if(commandData.command.substring(0, 7) == 'global:')
            {
                var command = commandData.command.substring(7);

                if(command == 'product-update')
                {
                    app.products.fetch({});
                }
            }
        }
        
        Trigger(command, callback)
        {
            this.ws.call.apply(this.ws, arguments);
        }        
    }

} );
