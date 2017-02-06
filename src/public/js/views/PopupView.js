define(["views/AbstractView"
], function(AbstractView) {
    "use strict";

    return class PopupView extends AbstractView {
        jqmAttributes() {
            return {'data-role': 'popup',
                    'data-theme': 'a'};
        }

        renderTemplate(Template, Datas) {
            super.renderTemplate(Template, Datas);
            this.$el.popup().enhanceWithin();
        }

    }
} );