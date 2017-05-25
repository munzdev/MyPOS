define(['views/helpers/HeaderView',
        'text!templates/admin/admin.phtml'
], function(HeaderView,
            Template) {
    "use strict";

    return class AdminView extends app.PageView {

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