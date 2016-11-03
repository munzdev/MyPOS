define([
    "models/db/Event/Event",
    "collections/db/Menu/MenuGroupCollection"
], function(Event,
            MenuGroupCollection){
    "use strict";

    return class MenuType extends Backbone.Model {
        
        idAttribute() { return 'MenuTypeid'; }

        defaults() {
            return {MenuTypeid: 0,
                    Eventid: 0,
                    Name: '',
                    Tax: 0,
                    Allowmixing: false};
        }
        
        parse(response)
        {
            if('Event' in response)
            {
                response.Event = new Event(response.Event, {parse: true});
            }
            
            if('MenuGroup' in response)
            {
                response.MenuGroup = new MenuGroupCollection(response.MenuGroup, {parse: true});
            }
            
            return super.parse(response);
        }

    }
});