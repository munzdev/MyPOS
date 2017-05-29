define(['views/helpers/HeaderView',
        'text!templates/manager/overview.phtml'
], function(HeaderView,
            Template) {
    "use strict";

    return class OverviewView extends app.ManagerView {

        // The View Constructor
        initialize() {
            this.render();
        }

        // Renders all of the Category models on the UI
        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template);

            this.changePage(this);

            return this;
        }
    }
});