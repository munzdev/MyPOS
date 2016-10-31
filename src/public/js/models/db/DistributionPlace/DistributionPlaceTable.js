define([
    "models/db/DistributionPlace/DistributionPlace",
    "models/db/Menu/MenuGroup",
    "models/db/Event/EventTable",
    "app"
], function(DistributionPlace,
            MenuGroup,
            EventTable){
    "use strict";

    return class DistributionPlaceTable extends Backbone.Model {
        
        idAttribute() { return 'EventTableid'; }

        defaults() {
            return {EventTableid: 0,
                    DistributionPlaceid: 0,
                    MenuGroupid: 0};
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
            
            return super.response(response);
        }

    }
});