define([
    "models/db/User/User",
    
], function(User){
    "use strict";

    return class Invoice extends app.BaseModel {
        
        idAttribute() { return 'Invoiceid'; }

        defaults() {
            return {Invoiceid: null,
                    CashierUserid: null,
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