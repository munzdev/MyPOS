define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class MenuType extends BaseModel {

        idAttribute() { return 'MenuTypeid'; }

        defaults() {
            return {MenuTypeid: null,
                    Eventid: null,
                    Name: '',
                    Tax: 0,
                    Allowmixing: false,
                    IsDeleted: null};
        }

        parse(response)
        {
            if('Event' in response)
            {
                response.Event = new app.models.Event.Event(response.Event, {parse: true});
            }

            if('MenuGroup' in response)
            {
                response.MenuGroup = new app.collections.Menu.MenuGroupCollection(response.MenuGroup, {parse: true});
            }

            return super.parse(response);
        }

    }
});