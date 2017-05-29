define(['views/helpers/HeaderView',
        'text!templates/admin/overview.phtml'
], function(HeaderView,
            Template) {
    "use strict";

    return class OverviewView extends app.AdminView {

    	initialize() {
            this.render();
        }

        render() {
            var header = new HeaderView();
            this.registerSubview(".nav-header", header);

            this.renderTemplate(Template);

            this.changePage(this);

            return this;
        }
    }

} );