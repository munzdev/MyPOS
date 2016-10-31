define([
    "models/db/User/User",
    "app"
], function(User){
    "use strict";

    return class UserMessage extends Backbone.Model {
        
        idAttribute() { return 'UserMessageid'; }

        defaults() {
            return {UserMessageid: 0,
                    FromEventUserid: 0,
                    ToEventUserid: 0,
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