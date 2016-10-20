/**
 * @desc        app globals
 */
define(["views/PageView",
        "views/DialogView",
        "views/PopupView",
        "jquery",
        "underscore",
        "backbone"
],
function(PageView,
         DialogView,
         PopupView) {
    "use strict";

    var app = {
        URL : "/",                      // Base application URL
        API : "API/",                   // Base API URL (used by models & collections)
    };

    $.ajaxSetup({ cache: false });      // force ajax call on all browsers

    // Global event aggregator
    app.eventAggregator = _.extend({}, Backbone.Events);
    
    // Define views
    app.PageView = PageView;
    app.DialogView = DialogView;
    app.PopupView = PopupView;

    return app;

});