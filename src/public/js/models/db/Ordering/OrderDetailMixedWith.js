define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class OrderDetailMixedWith extends BaseModel {

        defaults() {
            return {OrderDetailid: null,
                    Menuid: null};
        }

        parse(response)
        {
            if('OrderDetail' in response)
            {
                response.OrderDetail = new app.models.Ordering.OrderDetail(response.OrderDetail, {parse: true});
            }

            if('Menu' in response)
            {
                response.Menu = new app.models.Menu.Menu(response.Menu, {parse: true});
            }

            return super.parse(response);
        }
    }
});