define(['views/helpers/AdminFooterView'
], function(AdminFooterView) {
    "use strict";

    return class AdminEventView extends app.PageView {
        initialize(options) {
            super.initialize(options);
            this.eventid = options.eventid;
        }

        i18n() {
            return super.i18n('Admin.Event');
        }

        renderTemplate(Template, Datas) {
            let footer = new AdminFooterView(this.eventid);
            this.registerSubview(".nav-footer", footer);

            super.renderTemplate(Template, Datas);
        }

        getMenuLink() {
            return 'admin/event/modify/' + this.eventid;
        }
    }

} );