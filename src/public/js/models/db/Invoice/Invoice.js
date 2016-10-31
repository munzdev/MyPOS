define([
    "models/db/User/User",
    "app"
], function(User){
    "use strict";

    return class Invoice extends Backbone.Model {
        
        idAttribute() { return 'Invoiceid'; }

        defaults() {
            return {Invoiceid: 0,
                    CashierUserid: 0,
                    Date: null,
                    Canceled: null};
        }
        
        parse(response)
        {                       
            if('CashierUser' in response)
            {
                response.CashierUser = new User(response.CashierUser, {parse: true});
            }
            
            return super.parse(response);
        }

    }
});