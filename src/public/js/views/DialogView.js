define(["views/AbstractView"
], function(AbstractView) {
    "use strict";

    return class DialogView extends AbstractView {
        jqmAttributes() {
            return {'data-role': 'page',
                    'data-dialog': 'true',
                    'data-close-btn': 'none'};
        }
    }

} );