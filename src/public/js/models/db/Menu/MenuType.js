define([
    "models/db/Event/Event",
    "collections/db/Menu/MenuGroupCollection"
], function(Event,
            MenuGroupCollection){
    "use strict";

    return class MenuType extends app.BaseModel {
        
        idAttribute() { return 'MenuTypeid'; }

        defaults() {
            return {MenuTypeid: null,
                    Eventid: null,
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