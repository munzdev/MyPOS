define([
    "models/db/DistributionPlace/DistributionPlace",
    "models/db/Menu/MenuGroup",
    
], function(DistributionPlace,
            MenuGroup){
    "use strict";

    return class DistributionPlaceGroup extends app.BaseModel {
        
        defaults() {
            return {DistributionPlaceid: null,
                    MenuGroupid: null};
        }
        
        parse(response)
        {
            if('DistributionPlace' in response)
            {
                response.DistributionPlace = new DistributionPlace(response.DistributionPlace, {parse: true});
            }
            
            if('MenuGroup' in response)
            {
                response.MenuGroup = new MenuGroup(response.MenuGroup, {parse: true});
            }
            
            return super.parse(response);
        }

    }
});