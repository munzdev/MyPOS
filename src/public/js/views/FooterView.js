define(["views/AbstractView"
], function(AbstractView) {
    "use strict";

    return class FooterView extends AbstractView {
        renderTemplate(Template, Datas) {
            return this.renderTemplateToEl(Template, Datas);
        }

        jqmAttributes() {
            return {'data-role': 'footer',
                    'data-theme': 'b',
                    'data-position': 'fixed',
                    'data-tap-toggle': 'false'};
        }
    }

} );