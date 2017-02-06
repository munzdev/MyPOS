define(["views/AbstractView"
], function(AbstractView) {
    "use strict";

    return class HeaderView extends AbstractView {
        renderTemplate(Template, Datas) {
            return this.renderTemplateToEl(Template, Datas);
        }

        jqmAttributes() {
            return {'data-role': 'header',
                    'data-theme': 'b',
                    'data-position': 'fixed',
                    'data-tap-toggle': 'false'};
        }
    }

} );