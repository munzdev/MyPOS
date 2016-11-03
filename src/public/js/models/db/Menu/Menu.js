define([
    "models/db/Menu/MenuGroup",
    "models/db/Menu/Availability",
    "collections/db/Menu/MenuPossibleExtraCollection",
    "collections/db/Menu/MenuPossibleSizeCollection"
], function(MenuGroup,
            Availability,
            MenuPossibleExtraCollection,
            MenuPossibleSizeCollection){
    "use strict";

    return class Menu extends Backbone.Model {
        
        idAttribute() { return 'Menuid'; }

        defaults() {
            return {Menuid: 0,
                    MenuGroupid: 0,
                    Name: '',
                    Price: 0,
                    Availabilityid: 0,
                    AvailabilityAmount: 0};
        }

        parse(response)
        {
            if('MenuGroup' in response)
            {
                response.MenuGroup = new MenuGroup(response.MenuGroup, {parse: true});
            }
            
            if('Availability' in response)
            {
                response.Availability = new Availability(response.Availability, {parse: true});
            }
            
            if('MenuPossibleExtra' in response)
            {
                response.MenuPossibleExtra = new MenuPossibleExtraCollection(response.MenuPossibleExtra, {parse: true});
            }
            
            if('MenuPossibleSize' in response)
            {
                response.MenuPossibleSize = new MenuPossibleSizeCollection(response.MenuPossibleSize, {parse: true});
            }
           
            return super.parse(response);
        }
    }
});