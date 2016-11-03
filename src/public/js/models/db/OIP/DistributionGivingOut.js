define([
    
], function(){
    "use strict";

    return class DistributionGivingOut extends Backbone.Model {
        
        idAttribute() { return 'DistributionGivingOutid'; }

        defaults() {
            return {DistributionGivingOutid: 0,
                    Date: null};
        }

    }
});