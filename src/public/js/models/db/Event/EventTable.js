define([
    "app"
], function(app){
    "use strict";

    return class EventTable extends Backbone.Model {
        
        idAttribute() { return 'EventTableid'; }

        defaults() {
            return {EventTableid: 0,
                    Eventid: 0,
                    Name: '',
                    Data: ''};
        }

    }
});