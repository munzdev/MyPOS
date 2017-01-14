define(["views/AbstractView"],
function(AbstractView ) {
    "use strict";

    return class RenderView extends AbstractView {
        renderTemplate(Template, Datas) {
            return this.renderTemplateToEl(Template, Datas);
        }
    }

} );