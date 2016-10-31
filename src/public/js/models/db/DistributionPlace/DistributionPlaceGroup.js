define([
    "models/db/DistributionPlace/DistributionPlace",
    "models/db/Menu/MenuGroup",
    "app"
], function(DistributionPlace,
            MenuGroup){
    "use strict";

    return class DistributionPlaceGroup extends Backbone.Model {
        
        defaults() {
            return {DistributionPlaceid: 0,
                    MenuGroupid: 0};
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
            
            return super.response(response);
        }

    }
});