define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class Menu extends BaseModel {

        idAttribute() { return 'Menuid'; }

        defaults() {
            return {Menuid: null,
                    MenuGroupid: null,
                    Name: '',
                    Price: 0,
                    Availabilityid: null,
                    AvailabilityAmount: 0};
        }

        parse(response)
        {
            if('MenuGroup' in response)
            {
                response.MenuGroup = new app.models.Menu.MenuGroup(response.MenuGroup, {parse: true});
            }

            if('Availability' in response)
            {
                response.Availability = new app.models.Menu.Availability(response.Availability, {parse: true});
            }

            if('MenuPossibleExtra' in response)
            {
                response.MenuPossibleExtra = new app.collections.Menu.MenuPossibleExtraCollection(response.MenuPossibleExtra, {parse: true});
            }

            if('MenuPossibleSize' in response)
            {
                response.MenuPossibleSize = new app.collections.Menu.MenuPossibleSizeCollection(response.MenuPossibleSize, {parse: true});
            }

            return super.parse(response);
        }
    }
});