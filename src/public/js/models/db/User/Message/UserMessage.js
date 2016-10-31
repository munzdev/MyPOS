define([
    "app"
], function(app){
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

    }
});