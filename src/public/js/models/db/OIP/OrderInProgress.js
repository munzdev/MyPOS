define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class OrderInProgress extends BaseModel {

        idAttribute() { return 'OrderInProgressid'; }

        defaults() {
            return {OrderInProgressid: null,
                    Orderid: null,
                    Userid: null,
                    MenuGroupid: null,
                    Begin: null,
                    Done: null};
        }

        parse(response)
        {
            if('Order' in response)
            {
                response.Order = new app.models.Ordering.Order(response.Order, {parse: true});
            }

            if('User' in response)
            {
                response.User = new app.models.User.User(response.User, {parse: true});
            }

            if('MenuGroup' in response)
            {
                response.MenuGroup = new app.models.Menu.MenuGroup(response.MenuGroup, {parse: true});
            }

            return super.parse(response);
        }
    }
});