"use strict";
    
// Includes File Dependencies
define(["views/AbstractView",
        "views/PageView",
        "views/HeaderView",
        "views/DialogView",
        "views/PopupView",
        "collections/BaseCollection"],
function(AbstractView,
         PageView,
         HeaderView,
         DialogView,
         PopupView,
         BaseCollection) {

    // Define views
    app.AbstractView = AbstractView;
    app.PageView = PageView;
    app.HeaderView = HeaderView;
    app.DialogView = DialogView;
    app.PopupView = PopupView;
    
    // Define collections
    app.BaseCollection = BaseCollection;
    
    require(["app"]);
} );