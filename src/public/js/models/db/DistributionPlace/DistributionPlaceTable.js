define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class DistributionPlaceTable extends BaseModel {

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
                response.EventTable = new app.models.Event.EventTable(response.EventTable, {parse: true});
            }

            if('DistributionPlace' in response)
            {
                response.DistributionPlace = new app.models.DistributionPlace.DistributionPlace(response.DistributionPlace, {parse: true});
            }

            if('MenuGroup' in response)
            {
                response.MenuGroup = new app.models.Menu.MenuGroup(response.MenuGroup, {parse: true});
            }

            return super.parse(response);
        }

    }
});