define(["views/PageView"
], function(PageView) {
    "use strict";

    return class AdminEventView extends PageView {
        i18n() {
            return super.i18n('Admin.Event');
        }
    }

} );