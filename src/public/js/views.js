"use strict";
    
// Includes File Dependencies
define(["views/PageView",
        "views/DialogView",
        "views/PopupView"],
function(PageView,
         DialogView,
         PopupView) {

    // Define views
    app.PageView = PageView;
    app.DialogView = DialogView;
    app.PopupView = PopupView;
    
    require(["app"]);
} );