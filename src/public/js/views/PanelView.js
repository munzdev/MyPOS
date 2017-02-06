define(["views/AbstractView"
], function(AbstractView) {
    "use strict";

    return class PanelView extends AbstractView {
        jqmAttributes() {
            return {'data-role': 'panel',
                    'data-display': 'overlay',
                    'data-theme': 'a'};
        }

        renderTemplate(Template, Datas) {
            super.renderTemplate(Template, Datas);
            this.$el.panel().enhanceWithin();
        }

    }
} );