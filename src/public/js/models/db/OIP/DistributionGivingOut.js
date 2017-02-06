define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class DistributionGivingOut extends BaseModel {

        idAttribute() { return 'DistributionGivingOutid'; }

        defaults() {
            return {DistributionGivingOutid: null,
                    Date: null};
        }

    }
});