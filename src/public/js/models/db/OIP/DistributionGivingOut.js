define([
    
], function(){
    "use strict";

    return class DistributionGivingOut extends app.BaseModel {
        
        idAttribute() { return 'DistributionGivingOutid'; }

        defaults() {
            return {DistributionGivingOutid: null,
                    Date: null};
        }

    }
});