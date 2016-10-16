/**
 * @desc        app globals
 */
define([
    "jquery",
    "underscore",
    "backbone"
],
function($, _, Backbone) {
    "use strict";

    var app = {
        URL : "/",                      // Base application URL
        API : "API/",                   // Base API URL (used by models & collections)
    };

    $.ajaxSetup({ cache: false });      // force ajax call on all browsers

    // Global event aggregator
    app.eventAggregator = _.extend({}, Backbone.Events);

    return app;

});