define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class OrderDetailExtra extends BaseModel {

        defaults() {
            return {OrderDetailid: null,
                    MenuPossibleExtraid: null};
        }

        parse(response)
        {
            if('OrderDetail' in response)
            {
                response.OrderDetail = new app.models.Ordering.OrderDetail(response.OrderDetail, {parse: true});
            }

            if('MenuPossibleExtra' in response)
            {
                response.MenuPossibleExtra = new app.models.Menu.MenuPossibleExtra(response.MenuPossibleExtra, {parse: true});
            }

            return super.parse(response);
        }
    }
});