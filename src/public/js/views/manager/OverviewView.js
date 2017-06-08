define(['text!templates/manager/overview.phtml'
], function(Template) {
    "use strict";

    return class OverviewView extends app.ManagerView {

        // The View Constructor
        initialize() {
            this.render();
        }

        // Renders all of the Category models on the UI
        render() {
            this.renderTemplate(Template);

            this.changePage(this);
        }
    }
});