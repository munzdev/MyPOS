"use strict";
    
// Includes File Dependencies
define(["views/AbstractView",
        "views/PageView",
        "views/DialogView",
        "views/PopupView",
        "collections/BaseCollection"],
function(AbstractView,
         PageView,
         DialogView,
         PopupView,
         BaseCollection) {

    // Define views
    app.AbstractView = AbstractView;
    app.PageView = PageView;
    app.DialogView = DialogView;
    app.PopupView = PopupView;
    
    // Define collections
    app.BaseCollection = BaseCollection;
    
    require(["app"]);
} );