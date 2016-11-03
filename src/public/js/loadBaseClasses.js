"use strict";
    
// Includes File Dependencies
define(["views/PageView",
        "views/DialogView",
        "views/PopupView",
        "collections/BaseCollection"],
function(PageView,
         DialogView,
         PopupView,
         BaseCollection) {

    // Define views
    app.PageView = PageView;
    app.DialogView = DialogView;
    app.PopupView = PopupView;
    
    // Define collections
    app.BaseCollection = BaseCollection;
    
    require(["app"]);
} );