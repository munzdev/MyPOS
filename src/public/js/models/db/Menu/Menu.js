define([
    "models/db/Menu/MenuGroup",
    "models/db/Menu/Availability",
    
], function(MenuGroup,
            Availability){
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
            
            return super.parse(response);
        }
    }
});