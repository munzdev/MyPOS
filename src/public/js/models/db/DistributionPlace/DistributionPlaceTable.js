define([
    "models/db/DistributionPlace/DistributionPlace",
    "models/db/Menu/MenuGroup",
    "models/db/Event/EventTable",
    
], function(DistributionPlace,
            MenuGroup,
            EventTable){
    "use strict";

    return class DistributionPlaceTable extends app.BaseModel {
        
        idAttribute() { return 'EventTableid'; }

        defaults() {
            return {EventTableid: null,
                    DistributionPlaceid: null,
                    MenuGroupid: null};
        }
        
        parse(response)
        {
            if('EventTable' in response)
            {
                response.EventTable = new EventTable(response.EventTable, {parse: true});
            }
            
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