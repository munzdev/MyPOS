define(["models/BaseModel"
], function(BaseModel){
    "use strict";

    return class UserMessage extends BaseModel {

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
                response.FromEventUser = new app.models.User.User(response.FromEventUser, {parse: true});
            }

            if('ToEventUser' in response)
            {
                response.ToEventUser = new app.models.User.User(response.ToEventUser, {parse: true});
            }

            return super.parse(response);
        }
    }
});