define(['text!templates/admin/overview.phtml'
], function(Template) {
    "use strict";

    return class OverviewView extends app.AdminView {

    	initialize() {
            this.render();
        }

        render() {
            this.renderTemplate(Template);

            this.changePage(this);
        }
    }

} );