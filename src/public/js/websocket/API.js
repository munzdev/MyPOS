// Login Model
// ----------

//Includes file dependencies
define([ "app", "Webservice", "wampy"], function(app, Webservice)
{
    "use strict";

    function API()
    {
        _.bindAll(this, "onConnect",
                        "_unregisterChanel");

        var location = window.location;

        this.rolesSubscribed = [];

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

    API.prototype._registerChanel = function()
    {
        var self = this;

        var webservice = new Webservice();

        webservice.action = "Events/GetRoles";
        webservice.callback = {
            success: function(roles)
            {
                var user_roles = app.session.user.get('user_roles');

                _.each(roles, function(role) {
                    if(user_roles & role.events_user_roleid)
                    {
                        if(DEBUG) console.log("REGISTERED TO API ROLE: " + role.name);

                        self.rolesSubscribed.push(role);

                        self.ws.subscribe(role.events_user_roleid,
                                          self._commandRecieved);
                    }
                });
            }
        };
        webservice.call();
    }

    API.prototype._unregisterChanel = function()
    {
        var self = this;

        _.each(self.rolesSubscribed, function(role) {
            if(DEBUG) console.log("UNREGISTERED TO API ROLE: " + role.name);

            self.ws.unsubscribe(role.events_user_roleid);
        });

    }

    API.prototype._commandRecieved = function(data)
    {
        if(DEBUG) console.log("API DATA RECIEVED: " + data);

        var commandData = JSON.parse(data);

        if('apiCommandReciever' in app.router.currentView)
        {
            app.router.currentView.apiCommandReciever(commandData.command, commandData.options);
        }

        if(commandData.options.systemMessage)
        {
            app.ws.chat.SystemMessage(app.session.user.get('userid'), commandData.options.systemMessage);
        }

        if(commandData.command.substring(0, 7) == 'global:')
        {
            var command = commandData.command.substring(7);
        }
    }

    API.prototype.Trigger = function(command, callback)
    {
        this.ws.call(command, callback);
    }

    API.prototype.Connect = function()
    {
        this.ws.connect(this.websericeUrl);
    }

    API.prototype.Disconnect = function()
    {
        this._unregisterChanel();
        this.ws.disconnect();
    }

    API.prototype.onConnect = function ()
    {
        // WAMP session established here ..
        if(DEBUG) console.log("API-Verbindung hergestellt!");

        this._registerChanel();
    }

    API.prototype.onClose = function ()
    {
        if(DEBUG) console.log("API-Verbindung getrennt!");
    }

    return API;

} );
