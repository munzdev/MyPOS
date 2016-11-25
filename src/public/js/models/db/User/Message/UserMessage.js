define([
    "models/db/User/User",
    
], function(User){
    "use strict";

    return class UserMessage extends app.BaseModel {
        
        idAttribute() { return 'UserMessageid'; }

        defaults() {
            return {UserMessageid: null,
                    FromEventUserid: null,
                    ToEventUserid: null,
                    Message: '',
                    Date: null,
                    Readed: false};
        }

        parse(response)
        {
            if('FromEventUser' in response)
            {
                response.FromEventUser = new User(response.FromEventUser, {parse: true});
            }
            
            if('ToEventUser' in response)
            {
                response.ToEventUser = new User(response.ToEventUser, {parse: true});
            }
            
            return super.parse(response);
        }
    }
});