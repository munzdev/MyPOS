"use strict";

// Includes File Dependencies
define(["views/AbstractView",
        "views/PageView",
        "views/HeaderView",
        "views/DialogView",
        "views/PopupView",
        "views/PanelView",
        "views/RenderView",
        "collections/BaseCollection",
        "models/BaseModel"],
function(AbstractView,
         PageView,
         HeaderView,
         DialogView,
         PopupView,
         PanelView,
         RenderView,
         BaseCollection,
         BaseModel) {

    // Define views
    app.AbstractView = AbstractView;
    app.PageView = PageView;
    app.HeaderView = HeaderView;
    app.DialogView = DialogView;
    app.PopupView = PopupView;
    app.PanelView = PanelView;
    app.RenderView = RenderView;

    // Define collections and models
    app.BaseCollection = BaseCollection;
    app.BaseModel = BaseModel;

    require(["collections/db/Menu/MenuTypeCollection", // Verify that the Menu model/collection structures and depencies are loaded correctly by loading it first
             "collections/db/Invoice/InvoiceCollection", // Verify that Invoice model/collection structures and depencies are loaded correctly by loading it first
             "app"]);
} );