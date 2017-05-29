define(["views/PageView"
], function(PageView) {
    "use strict";

    return class ManagerView extends PageView {
        i18n() {
            return super.i18n('Manager');
        }
    }

} );