define(["views/AbstractView"
], function(AbstractView) {
    "use strict";

    return class PageView extends AbstractView {
        jqmAttributes() {
            return {'data-role': 'page'};
        }

        renderTemplate(Template, Datas) {
            super.renderTemplate(Template, Datas);

            // Verify global menu swipe is available on page
            this.$el.on("swiperight", app.sideMenu.open);
            this.$('.side-menu-open').click(app.sideMenu.open);
        }

        onClose() {
            this.$el.off();
        }
    }

} );