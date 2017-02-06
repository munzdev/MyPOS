define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class DistributionPlaceGroup extends BaseModel {

        defaults() {
            return {DistributionPlaceid: null,
                    MenuGroupid: null};
        }

        parse(response)
        {
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